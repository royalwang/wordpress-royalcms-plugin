<?php

namespace Royalcms\Component\Corcel;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

/**
 * Interface Shortcode
 *
 * @package Royalcms\Component\Corcel
 * @author Junior Grossi <juniorgro@gmail.com>
 */
interface Shortcode
{
    /**
     * @param ShortcodeInterface $shortcode
     * @return string
     */
    public function render(ShortcodeInterface $shortcode);
}
