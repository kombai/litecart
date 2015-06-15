<!DOCTYPE html>
<html lang="{snippet:language}">
<head>
<title>{snippet:title}</title>
<meta charset="{snippet:charset}" />
<meta name="keywords" content="{snippet:keywords}" />
<meta name="description" content="{snippet:description}" />
<meta name="robots" content="noindex, nofollow" />
<link href="{snippet:template_path}styles/loader.css" rel="stylesheet" media="screen" />
<link href="{snippet:template_path}styles/theme.css" rel="stylesheet" media="screen" />
<!--snippet:head_tags-->
<!--snippet:javascript-->
<script>
var $buoop = {c:2}; 
function $buo_f(){ 
  var e = document.createElement("script"); 
  e.src = "//browser-update.org/update.js"; 
  document.body.appendChild(e);
};
try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
catch(e){window.attachEvent("onload", $buo_f)}
</script>
</head>
<body>

<div id="body-wrapper">
  <div id="body">
    <div id="content-wrapper">
    <table style="width: 100%;">
      <tr>
        <td id="sidebar" style="vertical-align: top; width: 230px;">
          <div class="logotype">
            <a href="<?php echo document::href_link(WS_DIR_ADMIN); ?>"><img src="<?php echo functions::image_resample(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . 'logotype.png', FS_DIR_HTTP_ROOT . WS_DIR_CACHE, 220, 70, 'FIT_ONLY_BIGGER'); ?>" title="<?php echo settings::get('store_name'); ?>" /></a>
          </div>
          <div class="header">
            <a href="<?php echo document::href_ilink(''); ?>" title="<?php echo language::translate('title_catalog', 'Catalog'); ?>"><?php echo functions::draw_fonticon('fa-chevron-circle-left'); ?></a>
            <a href="<?php echo document::href_link(WS_DIR_ADMIN); ?>" title="<?php echo htmlspecialchars(language::translate('title_home', 'Home')); ?>"><?php echo functions::draw_fonticon('fa-home', '', 'fa-lg'); ?></a>
            <?php if (settings::get('webmail_link', '')) { ?><a href="<?php echo settings::get('webmail_link'); ?>" target="_blank" title="<?php echo language::translate('title_webmail', 'Webmail'); ?>"><?php echo functions::draw_fonticon('fa-envelope'); ?></a><?php } ?>
            <?php if (settings::get('database_admin_link', '')) { ?><a href="<?php echo settings::get('database_admin_link'); ?>" target="_blank" title="<?php echo language::translate('title_database_manager', 'Database Manager'); ?>"><?php echo functions::draw_fonticon('fa-database'); ?></a><?php } ?>
            <a href="<?php echo document::href_link(WS_DIR_ADMIN . 'logout.php'); ?>" title="<?php echo language::translate('text_logout', 'Logout'); ?>"><?php echo functions::draw_fonticon('fa-sign-out', '', 'fa-lg'); ?></a>
          </div>
          
          <!--snippet:dashboard-->
          
          <!--snippet:box_apps_menu-->
          
          <div class="footer">
            <div class="languages" style="text-align: center; margin-bottom: 10px 0 20px 0;">
<?php
  foreach (language::$languages as $language) {
    if ($language['status']) {
      echo '<a href="javascript:set_language(\''. $language['code'] .'\');"><img src="'. WS_DIR_IMAGES .'icons/languages/'. $language['code'] .'.png" alt="'. $language['name'] .'" /></a> ';
    }
  }
?>
<script>
  function set_language(code) {
    var form = $('<?php
      echo str_replace(array("\r", "\n"), '', functions::form_draw_form_begin('language_form', 'post')
                                            . functions::form_draw_hidden_field('set_language', '\'+ code +\'')
                                            . functions::form_draw_form_end()
      );
    ?>');
    $(document.body).append(form);
    form.submit();
  }
</script>
            </div>
            
            <div id="platform"><?php echo PLATFORM_NAME; ?> <?php echo PLATFORM_VERSION; ?></div>
            
            <div id="copyright">&copy; <?php echo date('2012-Y'); ?> LiteCart<br />
              <a href="http://www.litecart.net" target="_blank">www.litecart.net</a>
            </div>
          </div>
        </td>
        <td id="column_left" style="vertical-align: top;">
          <!--snippet:column_left-->
        </td>
        <td id="content" style="vertical-align: top;">
          <!--snippet:notices-->
          <!--snippet:content-->
        </td>
        <td id="column_right" style="vertical-align: top;">
          <!--snippet:column_right-->
        </td>
      </tr>
    </table>    
    </div>
  </div>
</div>

<!--snippet:foot_tags-->
</body>
</html>