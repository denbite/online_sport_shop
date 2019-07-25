<?php

use yii\db\Migration;

/**
 * Class m190622_095451_init
 */
class m190622_095451_init extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->unique()->notNull(),
            'phone' => $this->string(32)->unique()->notNull(),
            'password_hash' => $this->string()->notNull()->defaultValue(''),
            'auth_key' => $this->string(32)->notNull()->defaultValue(''),
            'email_confirm_token' => $this->string()->notNull()->defaultValue(''),
            'password_reset_token' => $this->string()->notNull()->defaultValue(''),
            'status' => $this->boolean()->unsigned()->defaultValue(1),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);
        
        $this->batchInsert(
            '{{%user}}',
            [ 'username', 'email', 'phone', 'password_hash', 'created_at', 'updated_at' ],
            [
                [ 'admin', 'mirloxd3@gmail.com', '+380675447477', '$2y$13$IhLqcYoo9q22IwLjFi7sLu3s5tYkteU/ngc27/2uf2HrOjAWjeVSi', time(), time() ],
                [ 'capcatd', 'captaincatd@ukr.net', '+380675225770', '$2y$13$IhLqcYoo9q22IwLjFi7sLu3s5tYkteU/ngc27/2uf2HrOjAWjeVSi', time(), time() ],
            ]
        );
        $auth = Yii::$app->authManager;
        
        if (!$auth->getRole('admin')) {
            $admin = $auth->createRole('admin');
            $admin->description = 'Administrator';
            $auth->add($admin);
            $auth->assign($admin, 1);
        }
        
        if (!$auth->getRole('user')) {
            $user = $auth->createRole('user');
            $user->description = 'User';
            $auth->add($user);
            $auth->assign($user, 2);
        }
        
        if (!$auth->getRole('guest')) {
            $guest = $auth->createRole('guest');
            $guest->description = 'Guest';
            $auth->add($guest);
        }
        
        
        $auth->addChild($admin, $user);
        $auth->addChild($user, $guest);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        
        $auth->removeAllPermissions();
        $auth->removeChild($auth->getRole('user'), $auth->getRole('guest'));
        $auth->removeChild($auth->getRole('admin'), $auth->getRole('user'));
        $auth->removeAllAssignments();
        $auth->removeAllRoles();
        
        $this->dropTable('{{%user}}');
    }
}
