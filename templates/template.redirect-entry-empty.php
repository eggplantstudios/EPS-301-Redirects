<?php
/**
 * 
 * EPS redirects
 * 
 * 
 * 
 * PHP version 5
 *
 *
 * @package    EPS 301 Redirects
 * @author     Shawn Wernig ( shawn@eggplantstudios.ca )
 * @version    1.3.4
 */


?>
<tr class="redirect-entry">
    <td>
        <select name="redirect[status][]" class="simple">
            <option value="301">301</option>
            <option value="302">302</option>
            <option value="inactive">Off</option>
        </select>
    </td>
    <td>
        <span class="eps-grey-text"><small><?php bloginfo('home'); ?>/&nbsp;</small></span>
        <input class="eps-request-url" type="text" name="redirect[url_from][]" value="" >
    </td>
    <td><?php echo eps_get_selector(); ?></td>
    <td></td>
    <td class="redirect-actions">&nbsp;</td>
</tr>