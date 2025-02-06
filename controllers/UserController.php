<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use app\models\User;
use yii\filters\AccessControl;

/**
 * Контроллер управления пользователями
 * Обеспечивает функционал управления пользователями системы
 */
class UserController extends Controller
{
    /**
     * Настройка прав доступа
     * Разрешает доступ только владельцу и администраторам с определенными ограничениями
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->role === 'owner' || 
                                   (Yii::$app->user->identity->role === 'admin' && $action->id === 'delete-user');
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Назначение пользователя администратором
     * @param integer $id ID пользователя
     * @throws ForbiddenHttpException если текущий пользователь не владелец
     */
    public function actionSetAdmin($id)
    {
        if (Yii::$app->user->identity->role !== 'owner') {
            throw new ForbiddenHttpException('Только владелец может назначать администраторов.');
        }

        $user = User::findOne($id);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Пользователь не найден.');
            return $this->redirect(['admin/users']);
        }

        $user->role = 'admin';
        if ($user->save()) {
            Yii::$app->session->setFlash('success', 'Пользователь назначен администратором.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при назначении администратора.');
        }

        return $this->redirect(['admin/users']);
    }

    /**
     * Снятие административных прав с пользователя
     * @param integer $id ID пользователя
     * @throws ForbiddenHttpException если текущий пользователь не владелец
     */
    public function actionRemoveAdmin($id)
    {
        if (Yii::$app->user->identity->role !== 'owner') {
            throw new ForbiddenHttpException('Только владелец может удалять администраторов.');
        }

        $user = User::findOne($id);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Пользователь не найден.');
            return $this->redirect(['admin/users']);
        }

        $user->role = 'user';
        if ($user->save()) {
            Yii::$app->session->setFlash('success', 'Администратор понижен до пользователя.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при понижении администратора.');
        }

        return $this->redirect(['admin/users']);
    }

    /**
     * Удаление пользователя
     * @param integer $id ID пользователя
     * @throws ForbiddenHttpException если текущий пользователь не владелец или администратор
     */
    public function actionDeleteUser($id)
    {
        $currentUserRole = Yii::$app->user->identity->role;
        
        if ($currentUserRole !== 'owner' && $currentUserRole !== 'admin') {
            throw new ForbiddenHttpException('Недостаточно прав для удаления пользователей.');
        }

        $user = User::findOne($id);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Пользователь не найден.');
            return $this->redirect(['admin/users']);
        }

        if ($currentUserRole === 'admin' && $user->role !== 'user') {
            throw new ForbiddenHttpException('Администратор может удалять только обычных пользователей.');
        }

        if ($user->role === 'owner') {
            throw new ForbiddenHttpException('Владелец не может быть удален.');
        }

        if ($user->delete()) {
            Yii::$app->session->setFlash('success', 'Пользователь успешно удален.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении пользователя.');
        }

        return $this->redirect(['admin/users']);
    }
}
