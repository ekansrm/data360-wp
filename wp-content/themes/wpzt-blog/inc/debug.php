<?php
function letan_url() {
  global $wp, $template;
  define("D4P_EOL", "\r\n");
  echo '<!-- Request: ';
  echo empty($wp->request) ? "None" : esc_html($wp->request); //输出请求
  echo ' -->'.D4P_EOL;
  echo '<!-- Matched Rewrite Rule: ';
  echo empty($wp->matched_rule) ? None : esc_html($wp->matched_rule); //输出翻译
  echo ' -->'.D4P_EOL;
  echo '<!-- Matched Rewrite Query: ';
  echo empty($wp->matched_query) ? "None" : esc_html($wp->matched_query); //输出查询参数
  echo ' -->'.D4P_EOL;
  echo '<!-- Loaded Template: ';
  echo basename($template); //输出模板名称
  echo ' -->'.D4P_EOL;
}

function letan_all_url() {
  global $wp_rewrite;
  echo '<div>';
  if (!empty($wp_rewrite->rules)) { //如果存在url翻译
    echo '<h5>Rewrite Rules</h5>';
    echo '<table><thead><tr>';
    echo '<td>Rule</td><td>Rewrite</td>';
    echo '</tr></thead><tbody>';
    foreach ($wp_rewrite->rules as $name => $value) { //输出翻译规则
      echo '<tr><td>'.$name.'</td><td>'.$value.'</td></tr>';
    }
    echo '</tbody></table>';
  } else {
    echo 'No rules defined.';
  }
  echo '</div>';
}
function letan_option_url(){
	$urllist=get_option('rewrite_rules');
	 echo '<div>';
  if (!empty($urllist)) { //如果存在url翻译
    echo '<h5>Rewrite Rules</h5>';
    echo '<table><thead><tr>';
    echo '<td>Rule</td><td>Rewrite</td>';
    echo '</tr></thead><tbody>';
    foreach ($urllist as $name => $value) { //输出翻译规则
      echo '<tr><td>'.$name.'</td><td>'.$value.'</td></tr>';
    }
    echo '</tbody></table>';
  } else {
    echo 'No rules defined.';
  }
  echo '</div>';
}
function wplog( $str = '', $tag = '' ) {
    $split = ( $tag=='' ) ? '' : ":\t";
    file_put_contents( WPZT_DIR.'/wp.log', $tag . $split . $str .date('Y-m-d H:i:s'). "\n", FILE_APPEND );
}