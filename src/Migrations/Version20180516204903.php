<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use App\Entity\Achievement;
use App\Entity\AchievementColor;
use App\Entity\AchievementGroup;
use App\Entity\Category;
use App\Entity\Wonder;
use App\Entity\WonderSet;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180516204903 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return EntityManagerInterface | object
     */
    private function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    public function up(Schema $schema)
    {
        $this->installCategories();
        $this->installWonders();
        $this->installAchievements();
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    private function installAchievements()
    {
        $colors = [
            'bronze' => [
                'name' => 'Bronze',
                'description' => 'Pretty easy to unlock. Nothing fancy is required',
                'sort_order' => 10,
            ],
            'silver' => [
                'name' => 'Silver',
                'description' => 'You need to put in some effort to unlock these',
                'sort_order' => 20,
            ],
            'gold' => [
                'name' => 'Gold',
                'description' => 'Not that easy to get. You may need to sacrifice a game and focus on the achievement',
                'sort_order' => 30,
            ],
            'platinum' => [
                'name' => 'Platinum',
                'description' => 'Almost impossible. All plannets may need to be alligned to get these',
                'sort_order' => 40,
            ],
        ];
        $groups = [
            'general' => [
                'name' => 'General',
                'sort_order' => 10,
            ],
            'military' => [
                'name' => 'Military',
                'sort_order' => 20,
            ],
            'cash' => [
                'name' => 'Cash',
                'sort_order' => 30,
            ],
            'wonder_stage' => [
                'name' => 'Wonder Stages',
                'sort_order' => 40,
            ],
            'civic' => [
                'name' => 'Civic',
                'sort_order' => 50,
            ],
            'trade' => [
                'name' => 'Trade',
                'sort_order' => 60,
            ],
            'science' => [
                'name' => 'Science',
                'sort_order' => 70,
            ],
            'guild' => [
                'name' => 'Guilds',
                'sort_order' => 80,
            ],
            'leaders' => [
                'name' => 'Leaders',
                'sort_order' => 90,
            ],
            'cities' => [
                'name' => 'Cities',
                'sort_order' => 100,
            ],
        ];

        $achievements = [
            'general_rookie' => [
                'name' => 'Rookie',
                'description' => 'Play 5 games.',
                'identifier' => 'rookie',
                'color' => 'bronze',
                'group' => 'general',
            ],
            'general_advanced' => [
                'name' => 'Advanced',
                'description' => 'Play 30 games.',
                'identifier' => 'advanced',
                'color' => 'silver',
                'group' => 'general',
            ],
            'general_legend' => [
                'name' => 'Legend',
                'description' => 'Play 100 games.',
                'identifier' => 'legend',
                'color' => 'gold',
                'group' => 'general',
            ],
            'general_specialist' => [
                'name' => 'Specialist',
                'description' => 'Score 0 or less in at least 2 categories and win',
                'identifier' => 'specialist',
                'color' => 'bronze',
                'group' => 'general',
            ],
            'general_guru' => [
                'name' => 'Guru',
                'description' => 'Score 0 or less in at least 3 categories and win',
                'identifier' => 'guru',
                'color' => 'silver',
                'group' => 'general',
            ],
            'general_polyglot' => [
                'name' => 'Polyglot',
                'description' => 'Score 1 or more in all categories and win',
                'identifier' => 'polyglot',
                'color' => 'silver',
                'group' => 'general',
            ],
            'general_evangelist' => [
                'name' => 'Evangelist',
                'description' => 'Score 0 or less in 5 categories and win',
                'identifier' => 'evangelist',
                'color' => 'gold',
                'group' => 'general',
            ],
            'general_wonderer' => [
                'name' => 'Wonderer',
                'description' => 'Win with 3 different wonders (official ones)',
                'identifier' => 'wonderer',
                'color' => 'bronze',
                'group' => 'general',
            ],
            'general_jack_of_all_trades' => [
                'name' => 'Jack of all trades',
                'description' => 'Win with all official wonders',
                'identifier' => 'jack_of_all_trades',
                'color' => 'silver',
                'group' => 'general',
            ],
            'general_plymath' => [
                'name' => 'Polymath',
                'description' => 'Win with all official wonders on both sides',
                'identifier' => 'polymath',
                'color' => 'gold',
                'group' => 'general',
            ],
            'general_chobo' => [
                'name' => 'Chobo',
                'description' => 'Win with 3 different unoffical wonders',
                'identifier' => 'chobo',
                'color' => 'bronze',
                'group' => 'general',
            ],
            'general_joongsu' => [
                'name' => 'Joongsu',
                'description' => 'Win with all unofficial wonders',
                'identifier' => 'joongsu',
                'color' => 'silver',
                'group' => 'general',
            ],
            'general_gosu' => [
                'name' => 'Gosu',
                'description' => 'Win with all unofficial wonders on both sides',
                'identifier' => 'gosu',
                'color' => 'gold',
                'group' => 'general',
            ],
            'cash_trump' => [
                'name' => 'Donald J. Trump',
                'description' => 'Collect 20 coins in one game',
                'identifier' => 'trump',
                'color' => 'bronze',
                'group' => 'cash',
            ],
            'cash_thiel' => [
                'name' => 'Peter Thiel',
                'description' => 'Collect 30 coins in one game',
                'identifier' => 'thiel',
                'color' => 'silver',
                'group' => 'cash',
            ],
            'cash_gates' => [
                'name' => 'Bill Gates',
                'description' => 'Collect 60 coins in one game',
                'identifier' => 'gates',
                'color' => 'gold',
                'group' => 'cash',
            ],
            'civic_gaudi' => [
                'name' => 'Antonio Gaudi',
                'description' => 'Score 25 points on civic (blue) cards in one game',
                'identifier' => 'gaudi',
                'color' => 'bronze',
                'group' => 'civic',
            ],
            'civic_corbusier' => [
                'name' => 'Le Corbusier',
                'description' => 'Score 40 points on civic (blue) cards in one game',
                'identifier' => 'corbusier',
                'color' => 'silver',
                'group' => 'civic',
            ],
            'civic_michelangelo' => [
                'name' => 'Michelangelo',
                'description' => 'Score 50 points on civic (blue) cards in one game',
                'identifier' => 'michelangelo',
                'color' => 'gold',
                'group' => 'civic',
            ],
            'civic_da_vinci' => [
                'name' => 'Da Vinci',
                'description' => 'Score 58 points on civic (blue) cards in one game',
                'identifier' => 'da_vinci',
                'color' => 'platinum',
                'group' => 'civic',
            ],
            'military_soldier' => [
                'name' => 'Soldier',
                'description' => 'Achieve 18+ points from military in 1 game ',
                'identifier' => 'soldier',
                'color' => 'bronze',
                'group' => 'military',
            ],
            'military_captain' => [
                'name' => 'Captain',
                'description' => 'Achieve 18+ points from military in 5 games',
                'identifier' => 'captain',
                'color' => 'silver',
                'group' => 'military',
            ],
            'military_general' => [
                'name' => 'General',
                'description' => 'Achieve 18+ points from military in 15 games',
                'identifier' => 'general',
                'color' => 'gold',
                'group' => 'military',
            ],
            'military_hedgehog' => [
                'name' => 'Hedgehog',
                'description' => 'Collect 100+ shields in all games',
                'identifier' => 'hedgehog',
                'color' => 'bronze',
                'group' => 'military',
            ],
            'military_armadillo' => [
                'name' => 'Armadillo',
                'description' => 'Collect 250+ shields in all games',
                'identifier' => 'armadillo',
                'color' => 'silver',
                'group' => 'military',
            ],
            'military_turtle' => [
                'name' => 'Turtle',
                'description' => 'Collect 500+ shields in all games',
                'identifier' => 'turtle',
                'color' => 'gold',
                'group' => 'military',
            ],
            'trade_trader' => [
                'name' => 'Trader',
                'description' => 'Achieve 7 points on trade (yellow) cards',
                'identifier' => 'trader',
                'color' => 'bronze',
                'group' => 'trade',
            ],
            'trade_bigshot' => [
                'name' => 'Big Shot',
                'description' => 'Achieve 13 points on trade (yellow) cards',
                'identifier' => 'big_shot',
                'color' => 'silver',
                'group' => 'trade',
            ],
            'trade_tycoon' => [
                'name' => 'Tycoon',
                'description' => 'Achieve 20 points on trade (yellow) cards',
                'identifier' => 'tycoon',
                'color' => 'gold',
                'group' => 'trade',
            ],
            'science_set_newton' => [
                'name' => 'Isaac Newton',
                'description' => 'Make 2+ science sets',
                'identifier' => 'newton',
                'color' => 'bronze',
                'group' => 'science',
            ],
            'science_set_curie' => [
                'name' => 'Marie Curie',
                'description' => 'Make 3+ science sets',
                'identifier' => 'curie',
                'color' => 'silver',
                'group' => 'science',
            ],
            'science_set_hawking' => [
                'name' => 'Stephen Hawking',
                'description' => 'Make 4+ science sets',
                'identifier' => 'hawking',
                'color' => 'gold',
                'group' => 'science',
            ],
            'science_set_einstein' => [
                'name' => 'Albert Einstein',
                'description' => 'Make 6+ science sets',
                'identifier' => 'einstein',
                'color' => 'platinum',
                'group' => 'science',
            ],
            'science_chain_ball_and_chain' => [
                'name' => 'Ball And Chain',
                'description' => '4+ of same science symbol in a game',
                'identifier' => 'ball_and_chain',
                'color' => 'bronze',
                'group' => 'science',
            ],
            'science_chain_chain_of_command' => [
                'name' => 'Chain Of Command',
                'description' => '5+ of same science symbol in a game',
                'identifier' => 'chain_of_command',
                'color' => 'silver',
                'group' => 'science',
            ],
            'science_chain_off_the_chain' => [
                'name' => 'Off The Chain',
                'description' => '7+ of same science symbol in a game',
                'identifier' => 'off_the_chain',
                'color' => 'gold',
                'group' => 'science',
            ],
            'science_chain_chain_reaction' => [
                'name' => 'Chain Reaction',
                'description' => '12+ of same science symbol in a game',
                'identifier' => 'chain_reaction',
                'color' => 'platinum',
                'group' => 'science',
            ],
            'wonder_stage_floreasca' => [
                'name' => 'Sky Tower Floreasca',
                'description' => 'Build 37 wonder stages',
                'identifier' => 'floreasca',
                'color' => 'bronze',
                'group' => 'wonder_stage',
            ],
            'wonder_stage_burj' => [
                'name' => 'Burj Khalifa',
                'description' => 'Build 163 wonder stages',
                'identifier' => 'burj',
                'color' => 'silver',
                'group' => 'wonder_stage',
            ],
            'wonder_stage_double_burj' => [
                'name' => 'Double Burj',
                'description' => 'Build 326 wonder stages',
                'identifier' => 'double_burj',
                'color' => 'gold',
                'group' => 'wonder_stage',
            ],
            'guild_fishmonger' => [
                'name' => 'Fishmonger',
                'description' => 'Get 20+ points in a game on guild (purple) cards',
                'identifier' => 'fishmonger',
                'color' => 'bronze',
                'group' => 'guild',
            ],
            'guild_blacksmith' => [
                'name' => 'Blacksmith',
                'description' => 'Get 30+ points in a game on guild (purple) cards',
                'identifier' => 'blacksmith',
                'color' => 'silver',
                'group' => 'guild',
            ],
            'guild_mason' => [
                'name' => 'Mason',
                'description' => 'Get 40+ points in a game on guild (purple) cards',
                'identifier' => 'mason',
                'color' => 'gold',
                'group' => 'guild',
            ],
            'leaders_churchill' => [
                'name' => 'Winston Churchill',
                'description' => 'Get 10+ points in a game on leaders (white) cards',
                'identifier' => 'churchill',
                'color' => 'bronze',
                'group' => 'leaders',
            ],
            'leaders_joan_darc' => [
                'name' => 'Joan D\'arc',
                'description' => 'Get 15+ points in a game on leaders (white) cards',
                'identifier' => 'joan_darc',
                'color' => 'silver',
                'group' => 'leaders',
            ],
            'leaders_caesar' => [
                'name' => 'Julius Caesar',
                'description' => 'Get 20+ points in a game on leaders (white) cards',
                'identifier' => 'caesar',
                'color' => 'gold',
                'group' => 'leaders',
            ],
            'cities_troy' => [
                'name' => 'Troy',
                'description' => 'Get 15+ points in a game on cities (black) cards',
                'identifier' => 'troy',
                'color' => 'bronze',
                'group' => 'cities',
            ],
            'cities_machu_picchu' => [
                'name' => 'Machu Picchu',
                'description' => 'Get 20+ points in a game on cities (black) cards',
                'identifier' => 'machu_picchu',
                'color' => 'silver',
                'group' => 'cities',
            ],
            'cities_atlantis' => [
                'name' => 'Atlantis',
                'description' => 'Get 30+ points in a game on cities (black) cards',
                'identifier' => 'atlantis',
                'color' => 'gold',
                'group' => 'cities',
            ],
        ];

        $objects = [
            'color' => [],
            'group' => [],
        ];
        $em = $this->getEntityManager();

        foreach ($colors as $colorKey => $colorSettings) {
            $color = new AchievementColor();
            $color->setName($colorSettings['name']);
            $color->setDescription($colorSettings['description']);
            $color->setSortOrder($colorSettings['sort_order']);
            $em->persist($color);
            $objects['color'][$colorKey] = $color;
        }

        foreach ($groups as $groupKey => $groupSettings) {
            $group = new AchievementGroup();
            $group->setName($groupSettings['name']);
            $group->setSortOrder($groupSettings['sort_order']);
            $em->persist($group);
            $objects['group'][$groupKey] = $group;
        }

        foreach ($achievements as $achievementSettings) {
            $achievement = new Achievement();
            $achievement->setName($achievementSettings['name']);
            $achievement->setAchievementColor($objects['color'][$achievementSettings['color']]);
            $achievement->setGroup($objects['group'][$achievementSettings['group']]);
            $achievement->setDescription($achievementSettings['description']);
            $achievement->setIdentifier($achievementSettings['identifier']);
            $em->persist($achievement);
        }
        $em->flush();
    }

    private function installCategories()
    {
        $categories = [
            [
                'name' => 'Military',
                'code' => 'military',
                'icon_class' => 'fa fa-shield red',
                'optional' => 0,
                'sort_order' => 1
            ],
            [
                'name' => 'Cash',
                'code' => 'cash',
                'icon_class' => 'fa fa-money',
                'optional' => 0,
                'sort_order' => 2
            ],
            [
                'name' => 'Wonder',
                'code' => 'wonder',
                'optional' => 0,
                'icon_class' => 'fa fa-signal yellow',
                'sort_order' => 3
            ],
            [
                'name' => 'Civic',
                'code' => 'civic',
                'icon_class' => 'fa fa-square blue',
                'optional' => 0,
                'sort_order' => 4
            ],
            [
                'name' => 'Trade',
                'code' => 'trade',
                'icon_class' => 'fa fa-square yellow',
                'optional' => 0,
                'sort_order' => 5
            ],
            [
                'name' => 'Science',
                'code' => 'science',
                'icon_class' => 'fa fa-flask green',
                'optional' => 0,
                'sort_order' => 6
            ],
            [
                'name' => 'Guilds',
                'code' => 'guild',
                'icon_class' => 'fa fa-square purple',
                'optional' => 0,
                'sort_order' => 7
            ],
            [
                'name' => 'Leaders',
                'code' => 'leaders',
                'icon_class' => 'fa fa-square leaders',
                'optional' => 1,
                'sort_order' => 8
            ],
            [
                'name' => 'Cities',
                'code' => 'cities',
                'icon_class' => 'fa fa-square black',
                'optional' => 1,
                'sort_order' => 9
            ],
        ];
        $entityManager = $this->getEntityManager();
        foreach ($categories as $settings) {
            $category = new Category();
            $category->setName($settings['name']);
            $category->setCode($settings['code']);
            $category->setSortOrder($settings['sort_order']);
            $category->setOptional($settings['optional']);
            $category->setIconClass($settings['icon_class']);
            $entityManager->persist($category);
        }
        $entityManager->flush();
    }

    private function installWonders()
    {
        $wonderGroups = [
            'standard' => [
                'name' => 'Standard',
                'active' => 1,
                'wonders' => [
                    [
                        'name' => 'Alexandria',
                        'active' => 1,
                    ],
                    [
                        'name' => 'Babylon',
                        'active' => 1,
                    ],
                    [
                        'name' => 'Ephesos',
                        'active' => 1,
                    ],
                    [
                        'name' => 'Gizah',
                        'active' => 1,
                    ],
                    [
                        'name' => 'Halikarnassus',
                        'active' => 1,
                    ],
                    [
                        'name' => 'Olympia',
                        'active' => 1,
                    ],
                    [
                        'name' => 'Rhodos',
                        'active' => 1,
                    ],
                ]
            ],
            'wonders-pack' => [
                'name' => 'Wonders Pack',
                'active' => 1,
                'wonders' => [
                    [
                        'name' => 'Stonehenge',
                        'active' => 1,
                    ],
                    [
                        'name' => 'Great Wall',
                        'active' => 1,
                        'playable_without_cities' => 0,
                    ],
                    [
                        'name' => 'Manneken Pis',
                        'active' => 1,
                    ],
                ]
            ],
            'leaders' => [
                'name' => 'Leaders',
                'active' => 1,
                'wonders' => [
                    [
                        'name' => 'Roma',
                        'active' => 1,
                        'playable_without_leaders' => 0,
                    ],
                    [
                        'name' => 'Abu simbel',
                        'active' => 1,
                        'playable_without_leaders' => 0,
                    ],
                ]
            ],
            'cities' => [
                'name' => 'Cities',
                'active' => 1,
                'wonders' => [
                    [
                        'name' => 'Petra',
                        'active' => 1,
                        'playable_without_cities' => 0,
                    ],
                    [
                        'name' => 'Byzantium',
                        'active' => 1,
                        'playable_without_cities' => 0,
                    ],
                ]
            ],
            'for-fun' => [
                'name' => 'For fun',
                'active' => 0,
                'wonders' => [
                    [
                        'name' => 'Babel',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Chichen Itza',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Antiocheia',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Dominion',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Angkor wat',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Citadeles',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Capua',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Urook',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Lhasa',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Brigadoon',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Helvetia',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Beiping',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Venezia',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Sparta',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Tartaros',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Nomades',
                        'active' => 0,
                        'official' => 0,
                    ],
                    [
                        'name' => 'Yamato',
                        'active' => 0,
                        'official' => 0,
                    ],

                ]
            ],
        ];
        $entityManager = $this->getEntityManager();
        foreach ($wonderGroups as $wonderGroup) {
            $set = new WonderSet();
            $set->setName($wonderGroup['name']);
            $set->setActive($wonderGroup['active']);
            $entityManager->persist($set);
            foreach ($wonderGroup['wonders'] as $wonderConfig) {
                $wonder = new Wonder();
                $wonder->setName($wonderConfig['name']);
                $wonder->setActive($wonderConfig['active']);
                if (isset($wonderConfig['playable_without_cities'])) {
                    $wonder->setPlayableWithoutCities($wonderConfig['playable_without_cities']);
                } else {
                    $wonder->setPlayableWithoutCities(true);
                }
                if (isset($wonderConfig['playable_with_cities'])) {
                    $wonder->setPlayableWithCities($wonderConfig['playable_with_cities']);
                } else {
                    $wonder->setPlayableWithCities(true);
                }
                if (isset($wonderConfig['playable_without_leaders'])) {
                    $wonder->setPlayableWithoutLeaders($wonderConfig['playable_without_leaders']);
                } else {
                    $wonder->setPlayableWithoutLeaders(true);
                }
                if (isset($wonderConfig['playable_with_leaders'])) {
                    $wonder->setPlayableWithLeaders($wonderConfig['playable_with_leaders']);
                } else {
                    $wonder->setPlayableWithLeaders(true);
                }
                if (isset($wonderConfig['official'])) {
                    $wonder->setOfficial($wonderConfig['official']);
                } else {
                    $wonder->setOfficial(1);
                }
                $wonder->setWonderSet($set);
                    $entityManager->persist($wonder);
            }
        }
        $entityManager->flush();
    }
}
