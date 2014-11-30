<?PHP

return [
    'propel' => [
        'database' => [
            'connections' => [
                'W101' => [
                    'adapter'    => 'sqlite',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'sqlite:w101-db-dev.sqlite',
                    'user'       => '',
                    'password'   => '',
                    'attributes' => []
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'W101',
            'connections' => ['W101']
        ],
        'generator' => [
            'defaultConnection' => 'W101',
            'connections' => ['W101']
        ]
    ]
];

?>
