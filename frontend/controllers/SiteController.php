<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\Post;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Profile;
use frontend\models\ImageUpload;
use frontend\models\Followers;
use frontend\models\PostLikes;
use frontend\models\Repost;
use frontend\models\Marker;
use frontend\models\MarkerImage;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],/*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function uloginSuccessCallback($attributes)
    {
        print_r($attributes);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            $profiles = Profile::find()
                ->where(['prime' => 1])
                ->andWhere(['ban' => 0])
                ->all();
        }else{
            $following = Followers::find()
            ->where(['id_follower' => Yii::$app->user->id])
            ->all();

            $array_profi = [];
            foreach($following as $follow){
                array_push($array_profi, $follow->user->profile->id);
            }

            $profiles = Profile::find()
                ->andWhere(['and',
                    ['prime'=>1],
                    ['ban'=>0]
                ])
                ->orWhere(['and',
                    ['id' => $array_profi],
                    ['ban'=>0]
                ])
                ->all();
        }

        $arr_users = array();

        foreach($profiles as $profile){
            array_push($arr_users, $profile->user->id);
        }

        $trips = Post::find()
        ->where(['id_author' => $arr_users])
        ->orderBy('created_at');

        $count = $trips->count();
        
        $reposts = Repost::find()
        ->where(['id_user' => $arr_users])
        ->orderBy('created_at');

        $count += $reposts->count();

        $all_array = array();

        foreach($trips->all() as $trip){
            array_push($all_array, $trip);
        }

        foreach($reposts->all() as $repost){
            array_push($all_array, $repost);
        }

        for($i=0; $i<count($all_array); $i++){
            for($j=$i+1; $j<count($all_array); $j++){
                if($all_array[$i]['created_at']<$all_array[$j]['created_at']){
                    $temp = $all_array[$j];
                    $all_array[$j] = $all_array[$i];
                    $all_array[$i] = $temp;
                }
           }         
        }

        $pagination = new Pagination([
            'defaultPageSize'   => 7,
            'totalCount'        => $count
        ]);

        $array_to_show = array();
        $base = 0;

        $count_to_view = $pagination->limit + $pagination->offset;
        if($count_to_view > $count) $count_to_view = $count;

        for($i=$pagination->offset; $i<$count_to_view; $i++){
            $array_to_show[$base] = $all_array[$i];
            $base++;
        }

        $session = Yii::$app->session;
        if(json_decode(Yii::$app->session->get('cities_list')) == null){
            $recently_searched = [];    
        }else{
            $recently_searched = json_decode(Yii::$app->session->get('cities_list'));
        }

        return $this->render('index',[
            'trips'             => $array_to_show,
            'pagination'        => $pagination,
            'recently_searched' => $recently_searched,
        ]);
    }

    public function setCookie($name, $value)

    {

        $cookie = new CHttpCookie($name, $value);

        $cookie->expire = time() + $time;

        $cookie->httpOnly = $disableClientCookies;   

        Yii::app()->request->cookies[$name] = $cookie;

    }

    public function actionSearching(){
        $place_id = Yii::$app->request->get('place_id');

        $trips = Post::find()
        ->where(['id_place' => $place_id])
        ->orderBy('created_at');

        $searchedCity = $trips->all()[0]['main_place_text'];
        $this->view->params['searching'] = 'true';
        $this->view->params['searchedCity'] = $searchedCity;

        if($trips->count() == 0) $this->view->params['searchedCity'] = 'Not Found';

            $session = Yii::$app->session;
            $session->open();

        if ($session->has('cities_list') && $trips->all()[0]['main_place_text']!="") {         //cookies

            $cities = [];
            $cities = json_decode(Yii::$app->session->get('cities_list'));
            
            $session->remove('cities_list');

            $current_city = [
                'place_id'  => $place_id,
                'main_text' => $trips->all()[0]['main_place_text'],
                'secondary_text' => $trips->all()[0]['secondary_place_name'],
            ];

            foreach($cities as $key => $city){
                if($current_city['place_id'] == $city->place_id){
                    unset($cities[$key]);
                }
            }

            $cities = array_values($cities);

            array_unshift($cities, $current_city);
            if(count($cities) > 5) $remove = array_pop($cities);
            
            $session->set('cities_list', json_encode($cities));
            
        }else if($trips->all()[0]['main_place_text']!=""){

            $current_city = [
                'place_id'  => $place_id,
                'main_text' => $trips->all()[0]['main_place_text'],
                'secondary_text' => $trips->all()[0]['secondary_place_name'],
            ];

            $array_cookie = [];
            array_push($array_cookie, $current_city);

            $session->set('cities_list', json_encode($array_cookie));
        }

        $recently_searched = json_decode(Yii::$app->session->get('cities_list'));
        
        $session->close();

        $trips_id = ArrayHelper::toArray($trips->all(), [
            'frontend\models\Post' => [
                'id',
            ],
        ]);

        $count = $trips->count();
        
        $reposts = Repost::find()
        ->where(['id_post' => ArrayHelper::getColumn($trips_id, 'id')])
        ->orderBy('created_at');

        $count += $reposts->count();

        $all_array = array();

        foreach($trips->all() as $trip){
            array_push($all_array, $trip);
        }

        foreach($reposts->all() as $repost){
            array_push($all_array, $repost);
        }

        for($i=0; $i<count($all_array); $i++){
            for($j=$i+1; $j<count($all_array); $j++){
                if($all_array[$i]['created_at']<$all_array[$j]['created_at']){
                    $temp = $all_array[$j];
                    $all_array[$j] = $all_array[$i];
                    $all_array[$i] = $temp;
                }
           }         
        }

        $pagination = new Pagination([
            'defaultPageSize'   => 7,
            'totalCount'        => $count
        ]);

        $array_to_show = array();
        $base = 0;

        $count_to_view = $pagination->limit + $pagination->offset;
        if($count_to_view > $count) $count_to_view = $count;

        for($i=$pagination->offset; $i<$count_to_view; $i++){
            $array_to_show[$base] = $all_array[$i];
            $base++;
        }

        return $this->render('index',[
            'trips'             => $array_to_show,
            'pagination'        => $pagination,
            'recently_searched' => $recently_searched,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';
            
            $this->layout = 'sign';
            $this->view->params['sign_type'] = 'login';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {

                    $model1 = new Profile();
                    $model1->user_id = Yii::$app->user->id;
                    $model1->save();

                    return $this->goHome();
                }
            }
        }

        $this->layout = 'sign';
        $this->view->params['sign_type'] = 'signup';
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionProfile(){

        if(Yii::$app->request->isAjax){
            $profi = Profile::findOne(['user_id' => Yii::$app->user->id]);

            $action = $_POST['action'];
            $model2 = new ImageUpload();
            
            if($action == "upload-back-image"){
                $model2->image = $_POST['image'];
                $model2->image_name = $_POST['image_name'];
                $model2->folder = 'profile/';
                if($profi->background_url == '1.png') $profi->background_url = 'img.jpg';
                $profi->background_url = $model2->uploadFile($profi->background_url);
                $profi->save();
                echo $profi->background_url;
                die();
            }else if($action == "upload-avatar-image"){
                $model2->image = $_POST['image'];
                $model2->image_name = $_POST['image_name'];
                $model2->folder = 'profile_avatar/';
                if($profi->avatar == 'circle.png') $profi->avatar = 'img.jpg';
                $profi->avatar = $model2->uploadFile($profi->avatar);
                $profi->save();
                echo $profi->avatar;
                die();
            }
        }

        $id = Yii::$app->request->get('id');
        $type = Yii::$app->request->get('type');
        

        $model = Profile::findOne(['user_id' => $id]);

        if($model == null){
            echo "Dont hack me, please";
            return;
        }
        if($model->birthday) $model->birthday = date("d-m-Y", $model->birthday);
        
        $folder_name = "profile/";

        if(Yii::$app->user->isGuest) $is_author = false;
        else if($id == Yii::$app->user->identity->id) $is_author = true;
        else $is_author = false;
        

        $this->layout = 'profile';
        $this->view->params['background'] = $folder_name . $model->background_url;
        $this->view->params['is_author'] = $is_author;
        

        if($type == 'trips_list'){
            $trips = Post::find()
            ->where(['id_author' => $id])
            ->orderBy('updated_at');

            $pagination = new Pagination([
                'defaultPageSize'   => 10,
                'totalCount'        => $trips->count()
            ]);

            $trips = $trips->offset($pagination->offset)->limit($pagination->limit)->all();
        }else if($type == 'liked_list'){
            $liked_post_id = PostLikes::find()
            ->where(['id_user' => $id])
            ->all();

            $data = ArrayHelper::toArray($liked_post_id, [
                'frontend\models\PostLikes' => [
                    'id_post',
                ],
            ]);

            $trips = Post::find()
            ->where(['id' => ArrayHelper::getColumn($data, 'id_post')])
            ->orderBy('updated_at');

            $pagination = new Pagination([
                'defaultPageSize'   => 10,
                'totalCount'        => $trips->count()
            ]);

            $trips = $trips->offset($pagination->offset)->limit($pagination->limit)->all();
        }else if($type == 'reposted_list'){
            $trips = Repost::find()
            ->where(['id_user' => $id])
            ->orderBy('created_at');

            $pagination = new Pagination([
                'defaultPageSize'   => 10,
                'totalCount'        => $trips->count()
            ]);

            $trips = $trips->offset($pagination->offset)->limit($pagination->limit)->all();
        }else if($type == 'travel_history'){
            $trips = Post::find()
            ->where(['id_author' => $id])
            ->all();

            $polilynes = [];
            foreach($trips as $trip){
                array_push($polilynes, unserialize($trip->polilynes));            
            }

            $polilynes = json_encode($polilynes);

            //image count
            $posts_id = ArrayHelper::toArray($trips, [
                'frontend\models\Post' => [
                    'id',
                ],
            ]);

            $markers = Marker::find()
            ->select(['id'])
            ->where(['id_post' => ArrayHelper::getColumn($posts_id, 'id')])
            ->all();

            $markers_id = ArrayHelper::toArray($markers, [
                'frontend\models\Marker' => [
                    'id',
                ],
            ]);

            $markers_image_count = MarkerImage::find()
            ->where(['id_marker' => ArrayHelper::getColumn($markers_id, 'id')])
            ->count();
        }

        if(Yii::$app->request->post('Profile')){
            $post =Yii::$app->request->post('Profile');
            $model->first_name = $post['first_name'];
            $model->second_name = $post['second_name'];
            $model->middle_name = $post['middle_name'];
            $model->birthday = strtotime($post['birthday']);
            $model->gender = $post['gender'];
            
            if($model->validate() && $model->save()){
                return $this->refresh();
            }
        }
        
        return $this->render( 'profile', [
            'model'         => $model,
            'is_author'     => $is_author,
            'current_type'  => $type,
            'trips'         => $trips,
            'id'            => $id,
            'pagination'    => $pagination,
            'polilynes'     => $polilynes,
            'image_maded'   => $markers_image_count
            ]);
    }

    public function actionUsersList(){
        if(!Yii::$app->user->identity->profile->admin) return;

        $profiles = Profile::find()
        ->where(['<>', 'id', Yii::$app->user->identity->profile->id])
        ->all();

        $this->layout = 'simple';
        return $this->render('users-list', [
            'profiles' => $profiles,
        ]);
    }

    public function actionUserPrime(){
        $id = Yii::$app->request->post('id');
        $profile = Profile::findOne([
            'id' => $id,
        ]);
    
        if($profile==null){   //якщо лайк не існує, то створимо його
            echo null;
            die();
        }
        
        if($profile->prime) $profile->prime = 0;
        else $profile->prime = 1;

        $profile->save();

        echo $profile->prime;
        die();
    }

    public function actionUserBan(){
        $id = Yii::$app->request->post('id');
        $profile = Profile::findOne([
            'id' => $id,
        ]);
    
        if($profile==null){   //якщо лайк не існує, то створимо його
            echo null;
            die();
        }
        
        if($profile->ban) $profile->ban = 0;
        else $profile->ban = 1;

        $profile->save();

        if($profile->ban == 0) {
            if($profile->prime) { echo '2'; die(); }
            else { echo '3'; die(); }
        }

        echo $profile->ban;
        die();
    }
}
