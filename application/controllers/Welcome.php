<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        \Txy::test();
        die;
        $req = Requests::get('http://www.baidu.com');
        var_dump($req);
        die();
		$id=(int)Input::get("project_id",1);
		$list=\ProjectModel::getList($id);

		View::assign('list',$list);
		View::render('project/page_list');
        
	}

	public function phpinfo()
	{
		phpinfo();die;
	}

	public function ip()
	{
		// 10.60.10.133 上海12F
		echo Input::ip_address();
	}
}