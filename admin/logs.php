<?php
 if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
    <h1>Apploxa - Notification Logs</h1>

    <?php apploxa_admin_nav('apploxa-logs'); ?>
    <div class="tab-content">
      <div class="container">



<table class="table table-striped table-hover " id="apploxa_logs_t">
  <thead>
    <tr>
      <th>#</th>
      <th scope="col">User</th>
      <th scope="col">WhatsApp Number</th>
      <th scope="col">Message</th>
      <th scope="col">Status</th>
      <th scope="col">Send Date</th>
    </tr>
  </thead>
  <tbody>
<?php  

global $wpdb;

$cache_key = 'logs_results';
$logs = wp_cache_get($cache_key); 
if ($logs === false) {
    $type =  null;
    $by = 'id';  
	  // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
      $logs = $wpdb->get_results(
          $wpdb->prepare(
              "SELECT * FROM {$wpdb->prefix}apploxa_logs 
              WHERE whats_app_number != %s  
              ORDER BY %s DESC",
              $type,
              $by
          )
      ,ARRAY_A); 
      wp_cache_set( $cache_key, $logs ); // Cache for one hour or adjust as needed.
}
     if(!empty($logs)):
     foreach ($logs as $log) : $user = get_userdata($log['user_id']);?>
    <tr>
      <td><?php echo esc_attr($log['id']) ?> </td>
      <?php if(isset($user->display_name)): ?>
      <td>
        <a target="_blank" href="<?php echo esc_url(admin_url()) . 'user-edit.php?user_id=' . esc_attr($log['user_id']) ?>">
          <?php echo esc_attr($user->display_name)?></a>
      </td>
    <?php else: ?>
      <td> user removed </td>
    <?php endif; ?>

      <td><?php echo esc_attr($log['whats_app_number']) ?></td>
      <td><?php echo esc_attr($log['message']) ?></td>
      <td><?php echo $log['status']==1 ? "Success" : "Failed" ;  ?></td>
      <td><?php echo esc_attr(gmdate('Y-m-d H:i', strtotime($log['created_at'] . ' +7 hours'))); ?></td>
    </tr>
  <?php endforeach; endif; ?>

  </tbody>
</table>
    </div>
      </div>
</div>
