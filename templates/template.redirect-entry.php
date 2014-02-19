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
<tr class="redirect-entry <?php echo $redirect->status; ?> id-<?php echo $redirect->id; ?>">
    <td><span>
        <select name="redirect[status][]" class="simple">
            <option value="301"   <?php echo ( $redirect->status == '301' ) ? 'selected="selected"' : null; ?>>301</option>
            <option value="302"   <?php echo ( $redirect->status == '302' ) ? 'selected="selected"' : null; ?>>302</option>
            <option value="inactive" <?php echo ( $redirect->status == 'inactive' ) ? 'selected="selected"' : null; ?>>Off</option>
        </select>
        </span>
    </td>
    <td>
        <span class="eps-grey-text"><small><?php bloginfo('home'); ?>/&nbsp;</small></span>
        <input class="eps-request-url" type="text" name="redirect[url_from][]" value="<?php echo $dfrom; ?>" >
    </td>
    <td>
        <?php echo eps_get_selector( $redirect ); ?>
    </td>
    <td class="text-center"><strong><?php echo $redirect->count; ?></strong></td>
    <td class="redirect-actions">
        <input type="hidden" class="redirect-id" name="redirect[id][]"  value="<?php echo $redirect->id; ?>" >
        <a class="eps-text-link test" href="<?php echo self::format_from_url( $redirect->url_from ); ?>" target="_blank">Test</a>
        <a class="eps-text-link remove eps-redirect-remove" href="#" class="">&times;</a>
    </td>
</tr>