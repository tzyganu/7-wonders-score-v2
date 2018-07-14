<?php
namespace App\Page;

use App\Controller\Edit;
use App\Controller\ListEntities;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Menu
{
    private $router;
    private $requestStack;
    public function __construct(
        UrlGeneratorInterface $router,
        RequestStack $requestStack
    ) {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }
    /**
     * @return mixed
     */
    private function getMenuConfig()
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'icon' => 'fa fa-dashboard',
                'active' => ['dashboard', ''],
                'url' => 'dashboard',
            ],
            'game' => [
                'label' => 'Games',
                'icon' => 'fa fa-id-card',
                'active' => ['game'],
                'children' => [
                    'new' => [
                        'label' => 'New Game',
                        'icon' => '',
                        'url' => 'game/new',
                        'active' => ['game/new'],
                    ],
                    'list' => [
                        'label' => 'View Games',
                        'icon' => '',
                        'url' => 'game/list',
                        'active' => ['game/list'],
                    ],
                ]
            ],
            'player' => [
                'label' => 'Players',
                'icon' => 'fa fa-user',
                'active' => ['player'],
                'children' => [
                    'new' => [
                        'label' => 'New Player',
                        'icon' => '',
                        'url' => Edit::DEFAULT_NEW_PATH,
                        'url-params' => [Edit::ENTITY_PARAM_NAME => 'player'],
                        'active' => ['player/new'],
                    ],
                    'list' => [
                        'label' => 'View Players',
                        'icon' => '',
                        'url' => ListEntities::DEFAULT_LIST_PATH,
                        'url-params' => [ListEntities::ENTITY_PARAM_NAME => 'player'],
                        'active' => ['player/list'],
                    ],
                ]
            ],
            'achievement' => [
                'label' => 'Achievements',
                'icon' => 'fa fa-trophy',
                'active' => ['achievement'],
                'children' => [
                    'achievement' => [
                        'label' => 'Achievements',
                        'icon' => '',
                        'url' => ListEntities::DEFAULT_LIST_PATH,
                        'url-params' => [ListEntities::ENTITY_PARAM_NAME => 'achievement'],
                        'active' => ['achievement'],
                    ],
                    'achievement-color' => [
                        'label' => 'Achievement Colors',
                        'icon' => '',
                        'url' => ListEntities::DEFAULT_LIST_PATH,
                        'url-params' => [ListEntities::ENTITY_PARAM_NAME => 'achievement-color'],
                        'active' => ['achievement-color'],
                    ],
                    'group' => [
                        'label' => 'Groups',
                        'icon' => '',
                        'active' => ['achievement-group'],
                        'url' => ListEntities::DEFAULT_LIST_PATH,
                        'url-params' => [ListEntities::ENTITY_PARAM_NAME => 'achievement-group'],
                    ],
                ]
            ],
            'wonder' => [
                'label' => 'Wonders',
                'icon' => 'fa fa-exclamation-triangle',
                'children' => [
                    'set' => [
                        'label' => 'Sets',
                        'icon' => '',
                        'active' => ['wonder-set'],
                        'children' => [
                            'new' => [
                                'label' => 'New Set',
                                'icon' => '',
                                'url' => Edit::DEFAULT_NEW_PATH,
                                'url-params' => [Edit::ENTITY_PARAM_NAME => 'wonder-set'],
                                'active' => ['wonder-set/new']
                            ],
                            'list' => [
                                'label' => 'View Sets',
                                'icon' => '',
                                'url' => ListEntities::DEFAULT_LIST_PATH,
                                'url-params' => [ListEntities::ENTITY_PARAM_NAME => 'wonder-set'],
                                'active' => ['wonder-set/list']
                            ],
                        ]
                    ],
                    'wonder' => [
                        'label' => 'Wonders',
                        'icon' => '',
                        'active' => ['wonder'],
                        'children' => [
                            'new' => [
                                'label' => 'New Wonder',
                                'icon' => '',
                                'url' => Edit::DEFAULT_NEW_PATH,
                                'url-params' => [Edit::ENTITY_PARAM_NAME => 'wonder'],
                                'active' => ['wonder/new']
                            ],
                            'list' => [
                                'label' => 'View Wonders',
                                'icon' => '',
                                'url' => ListEntities::DEFAULT_LIST_PATH,
                                'url-params' => [ListEntities::ENTITY_PARAM_NAME => 'wonder'],
                                'active' => ['wonder/list']
                            ],
                        ]
                    ],
                ],
            ],
            'report' => [
                'label' => 'Reports',
                'icon' => 'fa fa-table',
                'active' => ['report'],
                'children' => [
                    'player' => [
                        'label' => 'Player',
                        'icon' => '',
                        'url' => 'report/player',
                        'active' => ['report/player']
                    ],
                    'general' => [
                        'label' => 'General Reports',
                        'icon' => '',
                        'url' => 'report/list',
                        'active' => ['report/general', 'report/list']
                    ],
                ]
            ],
        ];
    }

    /**
     * @return string
     */
    public function render()
    {
        $menu = $this->getMenuConfig();
        $html = '';
        if (count($menu) > 0) {
            $html .= '<ul class="sidebar-menu" data-widget="tree">';
            foreach ($menu as $id => $item) {
                $html .= $this->renderItem($item, $id);
            }
            $html .= '</ul>';
        }
        return $html;
    }

    /**
     * @param array $item
     * @param $id
     * @param $selected
     * @return string
     */
    private function renderItem(array $item, $id)
    {
        $selected = $this->getSelectedPaths();
        $class = '';
        if ($this->hasChildren($item)) {
            $class .= ' treeview';
        }
        $activeWhen = [];
        if (isset($item['active'])) {
            $activeWhen = array_intersect($selected, $item['active']);
        }
        if (count($activeWhen) > 0) {
            $class .= ' active';
        }
        $html = '<li'.(($class) ? ' class="'.$class.'"' : '').' id="menu-item-'.$id.'">';
        $params = $item['url-params'] ?? [];
        $html.= '<a href="'.(array_key_exists('url', $item) ? $this->router->generate($item['url'], $params) : '#').'">';
        if (isset($item['icon'])) {
            $html .= '<i class="'.$item['icon'].'"></i>';
        }
        $html .= '<span>'.$item['label'].'</span>';
        if ($this->hasChildren($item)) {
            $html .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
        }
        $html .= "</a>";
        if ($this->hasChildren($item)) {
            $html .= '<ul class="treeview-menu">';
            foreach ($item['children'] as $childId => $child) {
                $html .= $this->renderItem($child, $id.'-'.$childId);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    /**
     * @param array $item
     * @return bool
     */
    private function hasChildren(array $item)
    {
        return isset($item['children']) && is_array($item['children']) && count($item['children']);
    }

    /**
     * @return array
     */
    private function getSelectedPaths()
    {
        $path = trim($uri = $this->requestStack->getCurrentRequest()->getPathInfo(), '/');
        $parts = explode('/', $path);
        $selected = [];
        foreach ($parts as $index => $value) {
            if (!isset($selected[$index - 1])) {
                $selected[$index] = $value;
            } else {
                $selected[$index] = $selected[$index - 1].'/'.$value;
            }
        }
        return $selected;
    }
}
