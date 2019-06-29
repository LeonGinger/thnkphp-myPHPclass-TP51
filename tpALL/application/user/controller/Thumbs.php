<?php
namespace app\user\controller;;
use think\Request;
use Org\CropAvatar;
use think\Controller;
class Thumbs extends Controller
{
    public function index(Request $request1) {
        //
       // $request = Request::instance();
       $request = $request1; 
       
        if ($request->isPost()) {
            //上传前先判断文件是否有错误
            if ($_FILES['avatar_file']['error'] !== 0) {
                $response = array('state' => 200,'message' => '文件过大或格式不对');
            } else {
                $options = $request->param('options');
                if ($options == 'cope') {
                    //裁剪操作，传入参数顺序不能乱
                    $crop = new CropAvatar(
                        isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
                        isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
                        isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null,
                        440,
                        220
                    ); //设置图片宽度 高度
                    //返回结果
                    $response = array(
                        'state'  => 200,
                        'message' => $crop -> getMsg(),
                        'result' => $crop -> getResult()
                    );
                    //删除裁剪的原图目录文件
                    removeDir(config('app.syc_images.original') . '/');
                } elseif ($options == 'not_cut') {
                    //不裁剪操作
                    $file = $request->file('avatar_file');
                    $filename = config('app.syc_images.thumb');
                    //验证
                    $info = $file->validate(['size'=>config('app.syc_images.size'),'ext'=>'jpg,png,gif'])->move($filename);
                    if ($info) {
                        $msg = '上传成功';
                        $result = ltrim($filename, ".") . '/' . date('Ymd') . '/' . $info->getFilename();
                    } else {
                        $msg = '原图片过大或格式不对';
                        $result = '';
                    }
                    $response = array(
                        'state'  => 200,
                        'message' => $msg,
                        'result' => $result,
                        'aa'=>$filename,

                    );
                }
            }
            //输出json(['code' => 0, 'msg' => $msg, 'data' => $data]);
            //{"state":200,"message":"上传成功","result":"\/uploads\/thumbs\/20190206\/201902062223108mfmq7.png"}
         //  return json_encode($response);
         return json($response);
           // var_dump($response);
        } else {
            return json('No data found!');
        }
    }
}