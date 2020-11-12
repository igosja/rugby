<?php

namespace frontend\components\widgets;

use common\models\db\Message;
use common\models\db\News;
use common\models\db\Support;
use common\models\db\Vote;
use common\models\db\VoteAnswer;
use common\models\db\VoteStatus;
use common\models\db\VoteUser;
use frontend\controllers\AbstractController;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class Menu
 * @package frontend\components\widgets
 *
 * @property array $menuItemList
 * @property array $menuItems
 * @property string $menu
 */
class Menu extends Widget
{
    public const ITEM_CHANGE_TEAM = 'changeTeam';
    public const ITEM_CHAT = 'chat';
    public const ITEM_FEDERATION = 'federation';
    public const ITEM_FORUM = 'forum';
    public const ITEM_HOME = 'home';
    public const ITEM_LOAN = 'loan';
    public const ITEM_MESSENGER = 'messenger';
    public const ITEM_NATIONAL_TEAM = 'nationalTeam';
    public const ITEM_NEWS = 'news';
    public const ITEM_PASSWORD = 'password';
    public const ITEM_PLAYER = 'player';
    public const ITEM_POLL = 'poll';
    public const ITEM_PROFILE = 'profile';
    public const ITEM_RATING = 'rating';
    public const ITEM_ROSTER = 'roster';
    public const ITEM_RULE = 'rule';
    public const ITEM_SCHEDULE = 'schedule';
    public const ITEM_SING_UP = 'signUp';
    public const ITEM_SUPPORT = 'support';
    public const ITEM_TEAM = 'team';
    public const ITEM_TOURNAMENT = 'tournament';
    public const ITEM_TRANSFER = 'transfer';
    public const ITEM_VIP = 'vip';

    /**
     * @var array $menuItemList
     */
    private array $menuItemList = [];

    /**
     * @var array $menuItems
     */
    private array $menuItems = [];

    /**
     * @var string $menu
     */
    private string $menu = '';

    /**
     * @return string
     */
    public function run(): string
    {
        $this->generateMenu();
        return $this->menu;
    }

    /**
     * @return void
     */
    private function generateMenu(): void
    {
        $this->setMenuItems();
        $this->menuItemsToHtml();
    }

    /**
     * @return void
     */
    private function setMenuItems(): void
    {
        if (Yii::$app->user->isGuest) {
            $this->setGuestMenuItems();
        } else {
            $this->setUserMenuItems();
        }
    }

    /**
     * @return void
     */
    private function setGuestMenuItems(): void
    {
        $this->menuItems = [
            [
                [
                    self::ITEM_HOME,
                    self::ITEM_NEWS,
                    self::ITEM_RULE,
                    self::ITEM_POLL,
                ],
                [
                    self::ITEM_SING_UP,
                    self::ITEM_PASSWORD,
                ],
            ],
            [
                [
                    self::ITEM_SCHEDULE,
                    self::ITEM_TOURNAMENT,
                    self::ITEM_TEAM,
                ],
                [
                    self::ITEM_PLAYER,
                    self::ITEM_TRANSFER,
                ],
            ],
            [
                [
                    self::ITEM_LOAN,
                    self::ITEM_FORUM,
                    self::ITEM_RATING,
                ],
            ]
        ];
    }

    /**
     * @return void
     */
    private function setUserMenuItems(): void
    {
        $this->menuItems = [
            [
                [
                    self::ITEM_HOME,
                    self::ITEM_NEWS,
                    self::ITEM_RULE,
                ],
                [
                    self::ITEM_VIP,
                    self::ITEM_POLL,
                    self::ITEM_CHANGE_TEAM,
                ],
            ],
            [
                [
                    self::ITEM_ROSTER,
                    self::ITEM_NATIONAL_TEAM,
                    self::ITEM_PROFILE,
                    self::ITEM_SCHEDULE,
                ],
                [
                    self::ITEM_TOURNAMENT,
                    self::ITEM_TEAM,
                    self::ITEM_PLAYER,
                ],
                [
                    self::ITEM_TRANSFER,
                    self::ITEM_LOAN,
                ],
            ],
            [
                [
                    self::ITEM_MESSENGER,
                    self::ITEM_FEDERATION,
                    self::ITEM_SUPPORT,
                ],
                [
                    self::ITEM_FORUM,
                    self::ITEM_CHAT,
                    self::ITEM_RATING,
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    private function menuItemsToHtml(): void
    {
        $this->menuItemsToLinkArray();
        $rows = [];
        foreach ($this->menuItems as $itemRows) {
            $rowsMobile = [];
            foreach ($itemRows as $itemRowsMobile) {
                foreach ($itemRowsMobile as $key => $value) {
                    if (strpos($value, 'hidden')) {
                        unset($itemRowsMobile[$key]);
                    }
                }
                $rowsMobile[] = implode(' | ', $itemRowsMobile);
            }
            $rows[] = implode(
                '<span class="hidden-xs"> | </span><br class="hidden-lg hidden-md hidden-sm">',
                $rowsMobile
            );
        }
        $this->menu = implode('<br/>', $rows);
    }

    /**
     * @return void
     */
    private function menuItemsToLinkArray(): void
    {
        $this->setMenuItemList();
        $rows = [];
        foreach ($this->menuItems as $itemRows) {
            $rowsMobile = [];
            foreach ($itemRows as $itemRowsMobile) {
                $items = [];
                foreach ($itemRowsMobile as $item) {
                    $items[] = Html::a(
                        $this->menuItemList[$item]['label'],
                        $this->menuItemList[$item]['url'],
                        [
                            'class' => $this->menuItemList[$item]['css'] ?? '',
                            'target' => $this->menuItemList[$item]['target'] ?? '',
                        ]
                    );
                }
                $rowsMobile[] = $items;
            }
            $rows[] = $rowsMobile;
        }
        $this->menuItems = $rows;
    }

    /**
     * @return void
     */
    private function setMenuItemList(): void
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $federationInfo = 0;
        $messenger = 0;
        $news = 0;
        $support = 0;
        $vote = 0;
        if (!Yii::$app->user->isGuest) {
            $support = Support::find()
                ->andWhere([
                    'user_id' => $controller->user->id,
                    'is_question' => false,
                    'read' => null,
                    'is_inside' => false
                ])
                ->count();

            $messenger = Message::find()
                ->andWhere(['to_user_id' => $controller->user->id, 'read' => null])
                ->count();

            $news = News::find()
                ->andWhere(['federation_id' => null])
                ->andWhere(['>', 'id', $controller->user->news_id])
                ->count();

            $vote = Vote::find()
                ->andWhere(['vote_status_id' => VoteStatus::OPEN, 'federation_id' => null])
                ->andWhere([
                    'not',
                    [
                        'id' => VoteAnswer::find()
                            ->select(['vote_id'])
                            ->andWhere([
                                'id' => VoteUser::find()
                                    ->select(['vote_answer_id'])
                                    ->andWhere(['user_id' => $controller->user->id])
                            ])
                    ]
                ])
                ->count();
            if ($controller->myTeam) {
                $federationNews = News::find()
                    ->andWhere(['federation_id' => $controller->myTeam->stadium->city->country->federation->id])
                    ->andWhere(['>', 'id', $controller->myTeam->federation_news_id])
                    ->count();

                $supportManager = Support::find()
                    ->andWhere(['federation_id' => $controller->myTeam->stadium->city->country->id, 'is_inside' => true, 'is_question' => false, 'read' => 0, 'user_id' => $controller->user->id])
                    ->count();

                $supportAdmin = 0;
                $supportPresident = 0;

                if (in_array($controller->user->id, [$controller->myTeam->stadium->city->country->federation->president_user_id, $controller->myTeam->stadium->city->country->federation->vice_user_id], true)) {
                    $supportAdmin = Support::find()
                        ->andWhere(['federation_id' => $controller->myTeam->stadium->city->country->federation->id, 'is_inside' => false, 'is_question' => false, 'read' => 0])
                        ->count();

                    $supportPresident = Support::find()
                        ->andWhere(['federation_id' => $controller->myTeam->stadium->city->country->federation->id, 'is_inside' => true, 'is_question' => true, 'read' => 0])
                        ->count();
                }

                $federationInfo = $federationNews + $supportManager + $supportAdmin + $supportPresident;

                if (!$vote) {
                    $vote = Vote::find()
                        ->andWhere([
                            'vote_status_id' => VoteStatus::OPEN,
                            'federation_id' => $controller->myTeam->stadium->city->country->federation->id,
                        ])
                        ->andWhere([
                            'not',
                            [
                                'id' => VoteAnswer::find()
                                    ->select(['vote_id'])
                                    ->andWhere([
                                        'id' => VoteUser::find()
                                            ->select(['vote_answer_id'])
                                            ->andWhere(['user_id' => Yii::$app->user->id])
                                    ])
                            ]
                        ])
                        ->count();
                }
            }
        }

        $nationalId = $controller->myNationalVice->id ?? $controller->myNational->id ?? 0;

        $this->menuItemList = [
            self::ITEM_CHANGE_TEAM => [
                'label' => 'Сменить клуб',
                'url' => ['/team-change/index'],
            ],
            self::ITEM_CHAT => [
                'label' => 'Чат',
                'target' => '_blank',
                'url' => ['/chat/index'],
            ],
            self::ITEM_FEDERATION => [
                'css' => $federationInfo ? 'red' : '',
                'label' => 'Федерация' . ($federationInfo ? ' <sup class="text-size-4">' . $federationInfo . '</sup>' : ''),
                'url' => ['/federation/news'],
            ],
            self::ITEM_FORUM => [
                'label' => 'Форум',
                'target' => '_blank',
                'url' => ['/forum/default/index'],
            ],
            self::ITEM_HOME => [
                'label' => 'Главная',
                'url' => ['/site/index'],
            ],
            self::ITEM_LOAN => [
                'label' => 'Аренда',
                'url' => ['/loan/index'],
            ],
            self::ITEM_MESSENGER => [
                'css' => $messenger ? 'red' : '',
                'label' => 'Сообщения' . ($messenger ? '<sup class="text-size-4">' . $messenger . '</sup>' : ''),
                'url' => ['*messenger/index'],
            ],
            self::ITEM_NATIONAL_TEAM => [
                'css' => $nationalId ? 'red' : 'hidden',
                'label' => 'Сборная',
                'url' => ['/national/view', 'id' => $nationalId],
            ],
            self::ITEM_NEWS => [
                'css' => $news ? 'red' : '',
                'label' => 'Новости' . ($news ? '<sup class="text-size-4">' . $news . '</sup>' : ''),
                'url' => ['/news/index'],
            ],
            self::ITEM_PASSWORD => [
                'label' => 'Забыли пароль?',
                'url' => ['/site/forgot-password'],
            ],
            self::ITEM_PLAYER => [
                'label' => 'Игроки',
                'url' => ['/player/index'],
            ],
            self::ITEM_POLL => [
                'css' => $vote ? 'red' : '',
                'label' => 'Опросы' . ($vote ? '<sup class="text-size-4">' . $vote . '</sup>' : ''),
                'url' => ['/vote/index'],
            ],
            self::ITEM_PROFILE => [
                'label' => 'Профиль',
                'url' => ['/user/view'],
            ],
            self::ITEM_RATING => [
                'label' => 'Рейтинги',
                'url' => ['/rating/index'],
            ],
            self::ITEM_ROSTER => [
                'css' => 'red',
                'label' => 'Ростер',
                'url' => ['/team/view'],
            ],
            self::ITEM_RULE => [
                'label' => 'Правила',
                'url' => ['/rule/index'],
            ],
            self::ITEM_SCHEDULE => [
                'label' => 'Рассписание',
                'url' => ['/schedule/index'],
            ],
            self::ITEM_SING_UP => [
                'css' => 'red',
                'label' => 'Регистрация',
                'url' => ['/site/sign-up'],
            ],
            self::ITEM_SUPPORT => [
                'css' => $support ? 'red' : '',
                'label' => 'Тех.поддержка' . ($support ? ' <sup class="text-size-4">' . $support . '</sup>' : ''),
                'url' => ['/support/index'],
            ],
            self::ITEM_TEAM => [
                'label' => 'Команды',
                'url' => ['/team/index'],
            ],
            self::ITEM_TOURNAMENT => [
                'label' => 'Турниры',
                'url' => ['/tournament/index'],
            ],
            self::ITEM_TRANSFER => [
                'label' => 'Трансфер',
                'url' => ['/transfer/index'],
            ],
            self::ITEM_VIP => [
                'label' => 'VIP клуб',
                'url' => ['/vip/index'],
            ],
        ];
    }
}
