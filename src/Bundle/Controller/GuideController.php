<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Bundle\Controller;

use Endroid\Guide\Guide;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class GuideController extends Controller
{
    /**
     * @Route("/", name="endroid_guide_index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $shows = $this->getGuide()->load();

        return [
            'shows' => $shows
        ];
    }

    /**
     * @Route("/rss", defaults={"_format"="xml"}, name="endroid_guide_rss")
     * @Template()
     *
     * @return array
     */
    public function rssAction()
    {
        $shows = $this->getGuide()->load();

        foreach ($shows as &$show) {
            foreach ($show['results'] as &$result) {
                $result['guid'] = hash('sha256', $show['label'] . $result['label']);
            }
        }

        return [
            'shows' => $shows
        ];
    }

    /**
     * @return Guide
     */
    protected function getGuide()
    {
        return $this->get('endroid_guide.guide');
    }
}
