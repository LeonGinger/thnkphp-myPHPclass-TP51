<?php  
namespace app\user\controller;
use think\Model;
use think\Controller;
use think\Db;
/**
 * 
 */
class Article extends Controller
{
	public function testimg(){
		js_imgupload();

	}
	public function webTotal(){
		$article_count=count(model('Article')->select());
		$category_count=count(model('Category')->select());
        $new_article=model('Article')->order('update_time','desc')->limit(5)->select();

		$data=[
			'article_count'=>$article_count,
			'category_count'=>$category_count,
			'new_article'=>$new_article,
		];
		return json($data);
		}

public function imgupload(){
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Support CORS
header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // finish preflight CORS requests here
}


if ( !empty($_REQUEST[ 'debug' ]) ) {
    $random = rand(0, intval($_REQUEST[ 'debug' ]) );
    if ( $random === 0 ) {
        header("HTTP/1.0 500 Internal Server Error");
        exit;
    }
}

// header("HTTP/1.0 500 Internal Server Error");
// exit;


// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Settings
// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
$targetDir = 'upload_tmp';
$uploadDir = 'upload';

$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds


// Create target dir
if (!file_exists($targetDir)) {
    @mkdir($targetDir);
}

// Create target dir
if (!file_exists($uploadDir)) {
    @mkdir($uploadDir);
}

// Get a file name
if (isset($_REQUEST["name"])) {
    $fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
    $fileName = $_FILES["file"]["name"];
} else {
    $fileName = uniqid("file_");
}
// $fileName=md5($fileName);
$myfilename=$fileName;

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

$uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;



// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


// Remove old temp files
if ($cleanupTargetDir) {
    if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
    }

    while (($file = readdir($dir)) !== false) {
        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        // If temp file is current file proceed to the next
        if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
            continue;
        }

        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
            @unlink($tmpfilePath);
        }
    }
    closedir($dir);
}


// Open temp file
if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
    }

    // Read binary input stream and append it to temp file
    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
} else {
    if (!$in = @fopen("php://input", "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
}

while ($buff = fread($in, 4096)) {
    fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

$index = 0;
$done = true;
for( $index = 0; $index < $chunks; $index++ ) {
    if ( !file_exists("{$filePath}_{$index}.part") ) {
        $done = false;
        break;
    }
}
if ( $done ) {
    if (!$out = @fopen($uploadPath, "wb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    }

    if ( flock($out, LOCK_EX) ) {
        for( $index = 0; $index < $chunks; $index++ ) {
            if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                break;
            }

            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }

            @fclose($in);
            @unlink("{$filePath}_{$index}.part");
        }

        flock($out, LOCK_UN);
    }
    @fclose($out);
}

// Return Success JSON-RPC response
$data=[
		'fileName'=> $myfilename,
		'filepath'=> $uploadPath,
			];
			return json($data);
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

}


	public function article_list(){
		header("Access-Control-Allow-Origin: *");
		$id=$this->request->param('id');
		if($id!='null' && (int)$id>1){
			$data=[
			'data'=>model('Article')->where('category_id',$id)->field('id,title')->select(),
		];
		return json($data);
		}else{
			$data=[
			'data'=>model('Article')->where('category_id',$id)->limit(0,8)->select(),
		];
		return json($data);
		}

		if($id=='null'){
		$data=[
			'data'=>model('Article')->select(),
		];
		return json($data);
		}
	}
	public function article_json(){
		header("Access-Control-Allow-Origin: *");
			$post=$this->request->param('cid');
			$artic_id=$this->request->param('id');
			$page=$this->request->param('page');
			if($artic_id!=NULL){
				$artic_id=(int)$artic_id;
				$data=model('Article')->where(['category_id'=>$post,'id'=>$artic_id])->select();
			 $size=count($data);
			 $list=json_encode($data);
		  	echo $list."size:".$size;
 			file_put_contents('article.json', $list);
			}else{
			$page=($page-1)*10;
			$post=(int)$post;
			 $data=model('Article')->where(['category_id'=>$post])->limit($page,10)->select();
			 $data_list=count(model('Article')->where(['category_id'=>$post])->select());
		 
		 $count_pgae=ceil($data_list/10);//页数



		 $list=json_encode($data);
		 $new_list = substr($list,0,strlen($list)-1); 
		 $new_list=$new_list.',{"count":'.$data_list.'}]';
		 		  echo $new_list;

		  file_put_contents('article.json', $list);
			}
		}
	public function category_json(){
		header("Access-Control-Allow-Origin: *");
		$post=$this->request->param('cid');
		$id=$this->request->param('id');

		if(!empty($id)){
		$category_data=model('Category')->where(['id'=>$id])->select();
		$data=[
			'data'=>$category_data,
		];
		return json($data);
	}

		if(!empty($post)){
		$category=model('Category')->where(['id'=>$post])->select();
		$list=json_encode($category);
		echo $list;
	}else{
		$page=$this->request->param('page');
		$page=($page-1)*10;
		$post=(int)$post;
		$category=model('Category')->limit($page,10)->select();
		$category_count=count(model('Category')->select());

		// echo $category_count;
		// exit;
		$data=[
			'category_list'=>$category,
			'len'=>$category_count,
		];
		return json($data);
	}
		// var_dump($list);

	


	}
	
	public function savearticle(){
		$data=$this->request->param('');
		// var_dump($data);
		// exit;
		$id=$this->request->param('id');

	
		if($id=='null'){
			unset($data['file']);
			// $test=remove_xss('<script>alert("xss")</script>');
			// var_dump($test);
			// exit;
			$result=model('Article')->add($data);
		}else{
			unset($data['file']);
			$id=(int)$id;
			$result=model('Article')->where('id',$id)->update($data);
		}
		

		return $result;
}
	public function savecategory(){
		$data=$this->request->post('');

		$id=$this->request->param('id');
		var_dump($id);  //WC!! string？？？is_null($id) 没用
		if($id=='null'){
			var_dump($data);
			$result=model('Category')->add($data);
		}else{
		 $result=model('Category')->where('id',$id)->update($data);
		}
		// var_dump($data);
		// exit;	
		return $result;
}
	public function upadtecategory(){
	$data=$this->request->post('');
	$id=$this->request->param('id');
	$result=model('Category')->where('id',$id)->update($data);
	return $result;
	}

	public function category_del(){
			$Category_id=$this->request->param('id','');
			Db::table('blog_Category')->where('id',$Category_id)->delete();
		}
	public function article_del(){
			$article_id=$this->request->param('id','');
			Db::table('blog_Article')->where('id',$article_id)->delete();
		}
	public function article_details(){
		header("Access-Control-Allow-Origin: *");
		$id=$this->request->param('id');
		
		$data=[
			'data' => model('Article')->where('id',$id)->select(),
		];
		return json($data);
	}
}