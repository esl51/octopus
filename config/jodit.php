<?php

$config = [
    'datetimeFormat' => 'm/d/Y g:i A',
    'quality' => 90,
    'defaultPermission' => 0775,
    'sources' => [
        'default' => [],
    ],
    'createThumb' => true,
    'thumbFolderName' => '_thumbs',
    'excludeDirectoryNames' => ['.tmb', '.quarantine'],
    'maxFileSize' => '8mb',
    'allowCrossOrigin' => false,
    'allowReplaceSourceFile' => true,
    'baseurl' => '/storage/media/',
    'root' => storage_path('app/public/media/'),
    'extensions' => ['jpg', 'png', 'gif', 'jpeg'],
    'imageExtensions' => ['jpg', 'png', 'gif', 'jpeg'],
    'debug' => config('app.debug'),
    'accessControl' => []
];
$config['roleSessionVar'] = 'JoditUserRole';
$config['accessControl'][] = [
    'role' => '*',
    'extensions' => '*',
    'path' => storage_path('app/public/media/'),
    'FILES' => true,
    'FILE_MOVE' => true,
    'FILE_UPLOAD' => true,
    'FILE_UPLOAD_REMOTE' => true,
    'FILE_REMOVE' => true,
    'FILE_RENAME' => true,
    'FOLDERS' => true,
    'FOLDER_MOVE' => true,
    'FOLDER_REMOVE' => true,
    'FOLDER_RENAME' => true,
    'IMAGE_RESIZE' => true,
    'IMAGE_CROP' => true,
];
$config['accessControl'][] = [
    'role' => '*',
    'extensions' => 'exe,bat,com,sh,swf',
    'FILE_MOVE' => false,
    'FILE_UPLOAD' => false,
    'FILE_UPLOAD_REMOTE' => false,
    'FILE_RENAME' => false,
];

return $config;
