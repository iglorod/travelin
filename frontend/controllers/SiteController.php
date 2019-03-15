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
use frontend\models\PostLikes;
use frontend\models\Repost;
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
        return $this->render('index');
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
        $id = Yii::$app->request->get('id');
        $type = Yii::$app->request->get('type');
        
        $model2 = new ImageUpload();

        $model = ($model = Profile::findOne(['user_id' => $id])) ? $model : new Profile();
        if($model->birthday) $model->birthday = date("d-m-Y", $model->birthday);
        
        $folder_name = "profile/";

        if(Yii::$app->user->isGuest) $is_author = false;
        else if($id == Yii::$app->user->identity->id) $is_author = true;
        else $is_author = false;
        
        if($model->user_id == "") { 
            $model->user_id = Yii::$app->user->id;
            $model->background_url = "1.png";
            $model->avatar = 'circle.png';
            $folder_name = "";
            $folder_avatar = "";
        }

        $this->layout = 'profile';
        $this->view->params['background'] = $folder_name . $model->background_url;
        

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
        }else if($type == 'liked_list'){
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
        if(Yii::$app->request->isAjax){
            $action = $_POST['action'];
            
            if($action == "upload-back-image"){
                $model2->image = $_POST['image'];
                $model2->image_name = $_POST['image_name'];
                $model2->folder = 'profile/';
                $model->background_url = $model2->uploadFile($model->background_url);
                $model->save();
                echo $model->background_url;
                die();
            }else if($action == "upload-avatar-image"){
                $model2->image = $_POST['image'];
                $model2->image_name = $_POST['image_name'];
                $model2->folder = 'profile_avatar/';
                if($model->avatar == 'circle.png') $model->avatar = 'img.jpg';
                $model->avatar = $model2->uploadFile($model->avatar);
                $model->save();
                echo $model->avatar;
                die();
            }
        }

        return $this->render( 'profile', [
            'model'         => $model,
            'is_author'     => $is_author,
            'current_type'  => $type,
            'trips'         => $trips,
            'id'            => $id,
            'pagination'    => $pagination
            ] );
    }
}
