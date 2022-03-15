<?php
// kleeja plugin
// content_mod
// version: 1.0
// developer: Hani Rouatbi & Kleeja Team

// prevent illegal run
if (! defined('IN_PLUGINS_SYSTEM'))
{
    exit();
}

// plugin basic information
$kleeja_plugin['content_mod']['information'] = [
    // the casucal name of this plugin, anything can a human being understands
    'plugin_title' => [
        'en' => 'Content Moderation',
        'ar' => 'الإشراف على المحتوى'
    ],
    // who is developing this plugin?
    'plugin_developer' => 'Hani Rouatbi & Kleeja Team',
    // this plugin version
    'plugin_version' => '1.0',
    // explain what is this plugin, why should i use it?
    'plugin_description' => [
        'en' => 'Invoke Moderation Models directly from your code. Automatically detect and filter objectionable content: Nudity, Hate, Weapons, Drugs, Embedded Texts, and more',
        'ar' => 'استدعاء نماذج الإشراف مباشرة من التعليمات البرمجية الخاصة بك. اكتشاف المحتوى المرفوض وتصفيته تلقائيا: العري والكراهية والأسلحة والمخدرات والنصوص المضمنة والمزيد'
    ],
    // min version of kleeja that's required to run this plugin
    'plugin_kleeja_version_min' => '2.0',
    // max version of kleeja that support this plugin, use 0 for unlimited
    'plugin_kleeja_version_max' => '3.9',
    // should this plugin run before others?, 0 is normal, and higher number has high priority
    'plugin_priority' => 0
];

//after installation message, you can remove it, it's not required
$kleeja_plugin['content_mod']['first_run']['ar'] = '
شكرا لإستخدامك للإضافة.<br>
هذه الإضافة تعمل بواجهة برمجة التطبيقات الخاصة بـ "<a href="https://sightengine.com/">sightengine</a>".
';

$kleeja_plugin['content_mod']['first_run']['en'] = "
Thank you for using our plugin.\n
This plugin works with the \"sightengine\" API.
";

//Plugin update function, called if plugin is already installed but version is different than current
$kleeja_plugin['content_mod']['update'] = function ($old_version, $new_version) {
    // if(version_compare($old_version, '0.5', '<')){
    // 	//... update to 0.5
    // }
    //
    // if(version_compare($old_version, '0.6', '<')){
    // 	//... update to 0.6
    // }

    //you could use update_config, update_olang
};

// Plugin Installation function
$kleeja_plugin['content_mod']['install'] = function ($plg_id) {
    
    add_olang([
        'CONFIG_KLJ_MENUS_CONTENT_MOD' => 'إعدادات الإشراف على المحتوى'
    ],
        'ar',
        $plg_id);

    $options = [
        'content_mod_apiuser' =>
        [
            'value'  => '',
            'html'   => configField('content_mod_apiuser'),
            'plg_id' => $plg_id,
            'type'   => 'content_mod'
        ],
        'content_mod_apisecret' =>
        [
            'value'  => '',
            'html'   => configField('content_mod_apisecret'),
            'plg_id' => $plg_id,
            'type'   => 'content_mod'
        ],
        'content_mod_workflow' =>
        [
            'value'  => '',
            'html'   => configField('content_mod_workflow'),
            'plg_id' => $plg_id,
            'type'   => 'content_mod'
        ]
    ];

    add_config_r($options);
    
    //new language variables
    add_olang(array(
        'CONTENT_MOD_APIUSER' => 'مستخدم واجهة برمجة التطبيقات',
        'CONTENT_MOD_APISECRET' => 'كلمة سر واجهة برمجة التطبيقات',
        'CONTENT_MOD_WORKFLOW' => 'معرف سير العمل الخاص بالصور',
        'CONTENT_MOD_INAPPROPRIATE' => 'يحتوي هذا الملف %s على محتوى غير لائق!'
        ),
        'ar',
        $plg_id);
    add_olang(array(
        'CONTENT_MOD_APIUSER' => 'API user',
        'CONTENT_MOD_APISECRET' => 'API secret',
        'CONTENT_MOD_WORKFLOW' => 'Image Workflow ID',
        'CONTENT_MOD_INAPPROPRIATE' => 'This file contains inappropriate content!'
        ),
        'en',
        $plg_id);
};

// plugin uninstalling, function to be called at uninstalling
$kleeja_plugin['content_mod']['uninstall'] = function ($plg_id) {
    //delete options
    delete_config(array(
        'content_mod_apiuser',
        'content_mod_apisecret',
        'content_mod_workflow'
    ));

    //delete language variables
    foreach (array('ar', 'en') as $language) {
        delete_olang(null, $language, $plg_id);
    }
};

// plugin functions
$kleeja_plugin['content_mod']['functions'] = [
    
    //Change Class Upload
    'begin_index_page' => function() {
        if (defined('DISABLE_CONTENT_MOD'))
        {
            return;
        }

        $uploadingMethodClass = dirname(__FILE__) . '/contentmodupload.php';

        return compact('uploadingMethodClass');
    },
];