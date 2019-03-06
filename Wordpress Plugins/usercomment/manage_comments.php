<?php ?>
<div class="manage_comments">
    <div class="comment_head">
        <h2>Manage User Comments</h2>
    </div>
    <div class="comment_body">
<?php
            global $wpdb;
            if($_GET['action']){
                $action = $_GET['action'];
                $ucomment_id= $_GET['cid'];
                switch ( $action ) {
                    case 'approveucomment' :
                        update_ucomment( $ucomment_id,'approve_ucomment');
                        echo "<h4>Comment approved.</h4>";
                        break;
                    case 'unapproveucomment' :
                        update_ucomment( $ucomment_id,'unapprove_ucomment' );
                        echo "<h4>Comment unapproved.</h4>";
                        break;
                    case 'deleteucomment' :
                        update_ucomment( $ucomment_id,'delete_ucomment' );
                        echo "<h4>Comment deleted.</h4>";
                        break;
                }
                //$redirect_url = admin_url("?page=manage_options");
                //$redirect_url = remove_query_arg(array('cid', 'action'),$redirect_url);
                //wp_redirect($redirect_url);
                //exit();
            }
            $where='';
            if($_GET['ucomment_status']){
                $comment_type = $_GET['ucomment_status'];
                
                switch ( $comment_type ) {
                    case 'all' :
                        $where = ' ';
                        break;
                    case 'approved' :
                        $where = ' where comment_approved=1 ';
                        break;
                    case 'pending' :
                        $where = ' where comment_approved=0 ';
                        break;
                }
                //$redirect_url = admin_url("?page=manage_options");
                //$redirect_url = remove_query_arg(array('cid', 'action'),$redirect_url);
                //wp_redirect($redirect_url);
                //exit();
            }
            
            
            
            $total= $wpdb->get_var("select count(id) from wp_user_comments $where");
            $pagenum = isset( $_GET['pnum'] ) ? absint( $_GET['pnum'] ) : 1;
            $limit = 20;      /*limit for the pagination per page*/
            $offset = ( $pagenum - 1 ) * $limit;
            $num_of_pages = ceil( $total / $limit );
            $paginatin=  " limit $offset,$limit ";
            
            $page_links = paginate_links( array(
                  'base' => get_permalink(get_option('manage_options')).'%_%',
                  'format'       => '?pnum=%#%',
                  'prev_text' => __( '&laquo; Previous'),
                  'next_text' => __( 'Next &raquo;'),
                  'total' => $num_of_pages,
                  'current' => $pagenum
                 ));
            
            $user_comments = $wpdb->get_results("SELECT * FROM wp_user_comments $where order by comment_date desc $paginatin");

        ?>
        
        <ul class="subsubsub">
            <li><a href="<?php echo '?page=manage_options&ucomment_status=all'; ?>" class="<?php echo ($_GET['ucomment_status']=='all' || $_GET['ucomment_status']=='') ? 'current' : '' ?>">All</a> |</li>
            <li><a href="<?php echo '?page=manage_options&ucomment_status=approved'; ?>" class="<?php echo ($_GET['ucomment_status']=='approved') ? 'current' : '' ?>">Approved</a> |</li>
            <li><a href="<?php echo '?page=manage_options&ucomment_status=pending'; ?>" class="<?php echo ($_GET['ucomment_status']=='pending') ? 'current' : '' ?>">Pending</a></li>
        </ul>
        <table class="list_comments wp-list-table widefat fixed striped comments">
            <thead>
                <tr><td>Comment Author</td>
                <td>Comment</td>
                <td>Commented On</td>
                <td>Submitted On</td></tr>
            </thead>
            <tbody>
               <?php
               if(empty($user_comments)){ ?>
               <tr><td colspan="4">No record(s) found.</td></tr>
               <?php
               }
               else{
                $row_count = 1;
                $row_class ='';
                foreach($user_comments as $ucomment){
                    if($row_count%2==0){
                        $row_class="even";
                    }else{
                        $row_class="odd";
                    }
               ?>
                <tr class="<?php echo $row_class; ?>">
                    <td><?php echo $ucomment->comment_author; ?></td>
                    <td><?php echo $ucomment->comment_content; ?>
                    <div class="row-actions visible">
                        <?php if($ucomment->comment_approved=="0"){ ?>
                        <span class=""><a href="<?php echo '?page=manage_options&cid='.$ucomment->id.'&action=approveucomment';?>">Approve</a> |</span>
                        <?php }else{ ?>
                        <span class=""><a href="<?php echo '?page=manage_options&cid='.$ucomment->id.'&action=unapproveucomment';?>">Unapprove</a> |</span>
                        <?php } ?>
                        <span class="delete"><a href="<?php echo '?page=manage_options&cid='.$ucomment->id.'&action=deleteucomment';?>">Delete</a></span>
                    </div>
                    </td>
                    <td><?php $userdata = get_userdata($ucomment->comment_user_id);
                        echo $userdata->first_name.' '.$userdata->last_name;
                    ?></td>
                    <td><?php echo $ucomment->comment_date; ?></td>
                </tr>
                <?php
                $row_count++;
                 }
                } ?>
            </tbody>
        </table>
    </div>
    <div class="pagination_links"><?php echo $page_links ?></div>
</div>