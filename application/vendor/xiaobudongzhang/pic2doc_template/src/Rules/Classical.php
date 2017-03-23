<?php
/**
 * Toolkit
 *
 * Licensed under the Massachusetts Institute of Technology
 *
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Lorne Wang < post@lorne.wang >
 * @copyright   Copyright (c) 2014 - 2015 , All rights reserved.
 * @link        http://lorne.wang/projects/toolkit
 * @license     http://lorne.wang/licenses/MIT
 */
namespace PHPTemplate\Rules;

class Classical extends Rule
{
    public function setting()
    {
        // {loop $items $item |$index,$count|}
        $this->add("/^loop\s+(\S+)\s+(\S+)\s*\|(\S+),(\S+)\|\s*$/", "<?php $3=-1;$4=count((array)$1);foreach((array) $1 as $2) { $3++; ?>");

        // {loop $items $item |$index|}
        $this->add("/^loop\s+(\S+)\s+(\S+)\s*\|(\S+)\|\s*$/", "<?php $3=-1;foreach((array) $1 as $2) { $3++; ?>");

        // {loop $items $item}
        $this->add("/^loop\s+(\S+)\s+(\S+)\s*$/", "<?php foreach((array) $1 as $2) { ?>");

        // {loop $items $key $value}
        $this->add("/^loop\s+(\S+)\s+(\S+)\s+(\S+)\s*$/", "<?php foreach($1 as $2 => $3) { ?>");

        // {/loop}
        $this->add("/^\/loop$/", "<?php } ?>");

        // {break true == 1}
        $this->add("/^break\s+(.+?)$/", "<?php if ($1) break; ?>");

        // {continue true == 1}
        $this->add("/^continue\s+(.+?)$/", "<?php if ($1) continue; ?>");

        // {if true == 1}
        $this->add("/^if\s+([^\}]+)$/", "<?php if ($1) { ?>");

        // {elseif true == 1}
        $this->add("/^elseif\s+([^\}]+)$/", "<?php } elseif ($1) { ?>");

        // {else}
        $this->add("/^else$/", "<?php } else { ?>");

        // {/if}
        $this->add("/^\/if$/", "<?php } ?>");

        // {$var}
        $this->add("/^=\s*(.+)$/", "<?php echo $1; ?>");

        // {$var|default}
        $this->add("/^(\\$\w+(?:(?:\[.+?\])|(?:->\w+))*)(?:\|(.+))?$/", "<?php echo isset($1) ? $1 : '$2'; ?>");

        // {template header}
        $this->add("/^template\s+[\"']?([^\}\"']+)[\"']?$/", "<?php PHPTemplate\TemplateManager::get('" . $this->id . "')->render('$1'); ?>");
    }
} 