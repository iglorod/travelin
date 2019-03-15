<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Post;
use frontend\models\PostSearch;
use frontend\models\ImageUpload;
use frontend\models\Marker;
use frontend\models\MarkerImage;
use frontend\models\PostLikes;
use frontend\models\Repost;
use frontend\models\RepostLikes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;


/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{

    public $model2;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
    return [
        'image-upload' => [
            'class' => 'vova07\imperavi\actions\UploadFileAction',
            'url' => '/frontend/web/uploads/post_images', // Directory URL address, where files are stored.
            'path' => '@frontend/web/uploads/post_images', // Or absolute path to directory where files are stored.
        ]
    ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $markers = [];
        $images = [];

        foreach($model->markers as $marker){
            $data = [
                'id'    => $marker['id'],
                'lat'   => $marker['lat'],
                'lng'   => $marker['lng'],
                'text'  => $marker['text'],
                'title' => $marker['title'],
            ];
            array_push($markers, $data);

            foreach($marker->images as $image){
                $data1 = [
                    'name'    => $image['name'],
                    'text'   => $image['text'],
                    'id_marker'   => $image['id_marker'],    
                ];
                array_push($images, $data1);
            }
        }

        $markers = json_encode($markers);
        $images = json_encode($images);
        $model->polilynes = json_encode(unserialize($model->polilynes));

        
        return $this->render('view', [
            'model'     => $model,
            'markers'   => $markers,
            'images'    => $images,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $model2 = new ImageUpload();

        if ($model->load(Yii::$app->request->post())) {
            $model->id_author = Yii::$app->user->id;
            $model->created_at = strtotime(date('Y-m-d H:i:s'));
            $model->updated_at = strtotime(date('Y-m-d H:i:s'));
            $model->polilynes = serialize(json_decode(stripslashes($model->result_polilyne)));

            $markers = json_decode(stripslashes($model->result_markers));

            if($model->save()){
                $post_id = $model->id;
                
                foreach ($markers as $key => $value){
                    $marker = new Marker();
                    $marker->id_post = $post_id;
                    $marker->lat = $value->lat;
                    $marker->lng = $value->lng;
                    $marker->title = $value->mainTitle;
                    $marker->text = $value->mainText;
                    if($marker->save()){
                        $marker_id = $marker->id;
                        foreach ($value->image as $key => $image){
                            $marker_image = new MarkerImage();
                            $marker_image->id_marker = $marker_id;
                            $marker_image->name = $image;
                            $marker_image->text = $value->text[$key];
                            $marker_image->save();
                        }
                    }
                }

            } 
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if(Yii::$app->request->isAjax){
            $action = $_POST['action'];
            
            if($action == "upload"){
                $model2->image = $_POST['image'];
                $model2->image_name = $_POST['image_name'];
                $model2->folder = 'marker_images/';
                $image_name = $model2->uploadFile('image.jpg');
                echo $image_name;
                die();
            }else if($action == "delete"){
                $deleteList = json_decode(stripslashes($_POST['image_list']));
                $model2->deleteOldFiles($deleteList);
                die();
            }
        }

        $this->layout = 'simple';
        return $this->render('create', [
            'model' =>  $model,
            'model2'=>  $model2,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionLikePost(){
        $id = Yii::$app->request->post('id');
        $likes = PostLikes::findOne([
            'id_post' => $id,
            'id_user'=>Yii::$app->user->identity->id,
        ]);

        if($likes==null){   //якщо лайк не існує, то створимо його
            $likes = new PostLikes();
            $likes->id_user=Yii::$app->user->identity->id;
            $likes->id_post=$id;
            $likes->save(false);
        }else{                 //якщо існує - видалимо
            $likes->deleteLike();
        }

        echo $likes->getCount($id);
        die();
    }

    public function actionLikeRepost(){
        $id = Yii::$app->request->post('id');
        $likes = RepostLikes::findOne([
            'id_repost' => $id,
            'id_user'=>Yii::$app->user->identity->id,
        ]);

        if($likes==null){   //якщо лайк не існує, то створимо його
            $likes = new RepostLikes();
            $likes->id_user=Yii::$app->user->identity->id;
            $likes->id_repost=$id;
            $likes->save(false);
        }else{                 //якщо існує - видалимо
            $likes->deleteLike();
        }

        echo $likes->getCount($id);
        die();
    }

    public function actionRepostPost(){
        $id = Yii::$app->request->post('id');
        $description = Yii::$app->request->post('description');

        $repost = Repost::findOne([
            'id_post' => $id,
            'id_user'=>Yii::$app->user->identity->id,
        ]);

        if($repost==null){   //якщо репост не існує, то створимо його
            $repost = new Repost();
            $repost->id_user = Yii::$app->user->identity->id;
            $repost->id_post = $id;
            $repost->description = $description;
            $repost->created_at = strtotime(date('Y-m-d H:i:s'));
            $repost->save(false);
        }

        return $this->redirect(['site/profile','id' => Yii::$app->user->identity->id, 'type' => 'reposted_list']);
    }
}