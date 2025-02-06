<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Управление пользователями';
$this->params['breadcrumbs'][] = ['label' => 'Админ-панель', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/admin/admin.css');
$this->registerCssFile('@web/css/admin/table-actions.css');

$currentUser = Yii::$app->user->identity;
?>

<div class="admin-users">
    <!-- Кнопка возврата -->
    <div class="mb-4">
        <a href="<?= Url::to(['/admin']) ?>" class="btn btn-outline-primary back-btn">
            <i class="fas fa-arrow-left"></i> К админ-панели
        </a>
    </div>

    <!-- Статистика пользователей -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-4">
                <div class="stats-card" style="background: #0d6efd;">
                    <div class="stats-info">
                        <h3>Всего пользователей</h3>
                        <div class="stats-number"><?= $totalCount ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <?php if ($currentUser->isOwner() || $currentUser->isAdmin()): ?>
            <div class="col-md-4">
                <div class="stats-card" style="background: #198754;">
                    <div class="stats-info">
                        <h3>Администраторов</h3>
                        <div class="stats-number"><?= $adminCount ?? 0 ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" style="background: #dc3545;">
                    <div class="stats-info">
                        <h3>Владельцев</h3>
                        <div class="stats-number"><?= $ownerCount ?? 1 ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Таблица пользователей -->
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя пользователя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->id ?></td>
                            <td><?= Html::encode($user->username) ?></td>
                            <td><?= Html::encode($user->email) ?></td>
                            <td>
                                <?php
                                $roleClasses = [
                                    'owner' => 'bg-danger',
                                    'admin' => 'bg-success',
                                    'user' => 'bg-primary'
                                ];
                                $roleLabels = [
                                    'owner' => 'Владелец',
                                    'admin' => 'Админ',
                                    'user' => 'Пользователь'
                                ];
                                $class = $roleClasses[$user->role] ?? 'bg-secondary';
                                $label = $roleLabels[$user->role] ?? $user->role;
                                ?>
                                <span class="badge <?= $class ?>"><?= $label ?></span>
                            </td>
                            <td class="action-column">
                                <div class="btn-group" role="group">
                                    <?php if ($currentUser->isOwner()): ?>
                                        <?php if ($user->role === 'user'): ?>
                                            <?= Html::a('<i class="fas fa-user-shield"></i>', ['/user/set-admin', 'id' => $user->id], [
                                                'class' => 'btn btn-sm btn-success',
                                                'title' => 'Назначить администратором',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите назначить этого пользователя администратором?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        <?php elseif ($user->role === 'admin'): ?>
                                            <?= Html::a('<i class="fas fa-user"></i>', ['/user/remove-admin', 'id' => $user->id], [
                                                'class' => 'btn btn-sm btn-warning',
                                                'title' => 'Снять права администратора',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите снять права администратора у этого пользователя?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (
                                        ($currentUser->isAdmin() && $user->role === 'user') || 
                                        ($currentUser->isOwner() && $user->role !== 'owner')
                                    ): ?>
                                        <?= Html::a('<i class="fas fa-trash"></i>', ['/user/delete-user', 'id' => $user->id], [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Удалить',
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.stats-card {
    border-radius: 8px;
    padding: 20px;
    color: white;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
}

.stats-info {
    position: relative;
    z-index: 1;
}

.stats-info h3 {
    font-size: 1.2rem;
    margin: 0;
    opacity: 0.9;
}

.stats-number {
    font-size: 2rem;
    font-weight: bold;
    margin-top: 10px;
}

.stats-icon {
    position: absolute;
    right: 20px;
    bottom: 20px;
    font-size: 3rem;
    opacity: 0.3;
}

.badge {
    font-size: 0.9rem;
    padding: 0.5em 0.8em;
}
</style>
