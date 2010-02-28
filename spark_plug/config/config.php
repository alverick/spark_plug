<?php

Configure::write('Project',array('name'=>'Spark Plug Cakephp Plugin'));


Configure::write('UserPermissions',array(
				'controllers/Posts/index',
				'controllers/Posts/edit',
				'controllers/Websites'
));

Configure::write('rootURL','localhost/sparky');
Configure::write('httpRootURL','http://localhost/sparky');
Configure::write('projectName','Spark Plug Cakephp Plugin');
Configure::write('logged-in-menu','logged_in_menu');
Configure::write('front_end_layout','default');
Configure::write('dashboard_layout','default');

function SparkPlugIt(&$controller)
{
    $pageRedirect = $controller->Session->read('permission_error_redirect');
    $controller->Session->delete('permission_error_redirect');

    $controller->company_id = $controller->Session->read('Company.id');

    if (empty($pageRedirect))
    {
        $actionUrl = $controller->params['url']['url'];

//        if (isset($controller->params['slug']))
//            $website = $controller->Website->find('Website.subdomain = "'.$controller->params['slug'].'"');
//        else
//            $website = null;
//
//        if (!$website)
//        {
            $user = $controller->Authsome->get();
            if (!$user)
            {
                //anonymous?
                if (!$controller->UserGroup->isGuestAccess($actionUrl))
                {
                    $controller->Session->write('permission_error_redirect','/users/login');
                    $controller->Session->setFlash('Sorry, You don\'t have permission to view this page.');

                    $controller->redirect('/users/login');
                }
            }
            else
            {
                if (!$controller->UserGroup->isUserGroupAccess($user['User']['user_group_id'],$actionUrl))
                {
                    $controller->Session->write('permission_error_redirect','/users/login');
                    $controller->Session->setFlash('Sorry, You don\'t have permission to view this page. '.$user['User']['user_group_id'].':('.$actionUrl.')');

                    $controller->redirect('/users/dashboard');
                }
            }
//        }
    }
}
?>