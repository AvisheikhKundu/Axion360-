<?php
/**
 * Contact form leads
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}

global $wpdb;
$tableName = $wpdb->prefix.'chaty_contact_form_leads';

$paged   = filter_input(INPUT_GET, 'paged');
$current = isset($paged)&&!empty($paged)&&is_numeric($paged)&&$paged > 0 ? $paged : 1;
$current = intval($current);

$searchFor  = "all_time";
$searchList = [
    'today'        => esc_html__('Today', 'chaty'),
    'yesterday'    => esc_html__('Yesterday', 'chaty'),
    'last_7_days'  => esc_html__('Last 7 Days', 'chaty'),
    'last_30_days' => esc_html__('Last 30 Days', 'chaty'),
    'this_week'    => esc_html__('This Week', 'chaty'),
    'this_month'   => esc_html__('This Month', 'chaty'),
    'all_time'     => esc_html__('All Time', 'chaty'),
    'custom'       => esc_html__('Custom Date', 'chaty')
];

$searchFor = filter_input(INPUT_GET, 'search_for');
if (isset($searchFor) && !empty($searchFor) && isset($searchList[$searchFor])) {
    $searchFor = esc_attr($searchFor);
} else {
    $searchFor = "all_time";
}

$startDate = "";
$endDate   = "";
if ($searchFor == "today") {
    $startDate = gmdate("Y-m-d");
    $endDate   = gmdate("Y-m-d");
} else if ($searchFor == "yesterday") {
    $startDate = gmdate("Y-m-d", strtotime("-1 days"));
    $endDate   = gmdate("Y-m-d", strtotime("-1 days"));
} else if ($searchFor == "last_7_days") {
    $startDate = gmdate("Y-m-d", strtotime("-7 days"));
    $endDate   = gmdate("Y-m-d");
} else if ($searchFor == "last_30_days") {
    $startDate = gmdate("Y-m-d", strtotime("-30 days"));
    $endDate   = gmdate("Y-m-d");
} else if ($searchFor == "this_week") {
    $startDate = gmdate("Y-m-d", strtotime('monday this week'));
    $endDate   = gmdate("Y-m-d");
} else if ($searchFor == "this_month") {
    $startDate = gmdate("Y-m-01");
    $endDate   = gmdate("Y-m-d");
} else if ($searchFor == "custom") {
    $startDate = filter_input(INPUT_GET, 'start_date');
    if (!empty($startDate)) {
        $startDate = esc_attr($startDate);
    } else {
        $startDate = "";
    }

    $endDate = filter_input(INPUT_GET, 'end_date');
    if (!empty($endDate)) {
        $endDate = esc_attr($endDate);
    } else {
        $endDate = "";
    }
}//end if

$hasSearch = filter_input(INPUT_GET, 'search');
$hasSearch = isset($hasSearch)&&!empty($hasSearch) ? $hasSearch : false;

$query  = "SELECT count(id) as total_records FROM ".$tableName;
$search = "";

$condition      = "";
$conditionArray = [];
if ($hasSearch !== false) {
    $search           = $hasSearch;
    $hasSearch        = '%'.esc_sql($hasSearch).'%';
    $condition       .= " (name LIKE %s OR email LIKE %s OR phone_number LIKE %s OR message LIKE %s)";
    $conditionArray[] = $hasSearch;
    $conditionArray[] = $hasSearch;
    $conditionArray[] = $hasSearch;
    $conditionArray[] = $hasSearch;
}

$startDate = esc_attr($startDate);
$endDate   = esc_attr($endDate);
if (!empty($startDate) && !empty($endDate)) {
    if (!empty($condition)) {
        $condition .= " AND ";
    }

    $cStartDate       = gmdate("Y-m-d 00:00:00", strtotime($startDate));
    $cEndDate         = gmdate("Y-m-d 23:59:59", strtotime($endDate));
    $condition       .= " created_on >= %s AND created_on <= %s";
    $conditionArray[] = $cStartDate;
    $conditionArray[] = $cEndDate;
}

if (!empty($condition)) {
    $query .= " WHERE ".$condition;
}

$query .= " ORDER BY ID DESC";

if (!empty($conditionArray)) {
    $query = $wpdb->prepare($query, $conditionArray);
}

$totalRecords = $wpdb->get_var($query);
$perPage      = 15;
$totalPages   = ceil($totalRecords / $perPage);

$query = "SELECT * FROM ".$tableName;
if (!empty($condition)) {
    $query .= " WHERE ".$condition;
}

if ($current > $totalPages) {
    $current = 1;
}

$startFrom = (($current - 1) * $perPage);

$query .= " ORDER BY ID DESC";
$query .= " LIMIT $startFrom, $perPage";

if (!empty($conditionArray)) {
    $query = $wpdb->prepare($query, $conditionArray);
}
?>
<div class="wrap">
    <?php
    $result = $wpdb->get_results($query);
    ?>
    <div>
        <?php if ($result || !empty($search) || $searchFor != 'all_time') { ?>
        <!-- header top -->
        <div class="flex flex-wrap justify-between pt-5">
            <a href="<?php echo esc_url( $this->getDashboardUrl() ) ?>">
                <img class="w-32" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/logo-color.svg'); ?>" alt="Chaty">
            </a>
            <span class="mt-3 sm:mt-0 font-primary text-3xl text-cht-gray-150"><?php esc_html_e('Contact Form Leads', 'chaty') ?></span>
        </div>

        <!-- header -->
        <div class="flex flex-wrap gap-3 justify-between items-center contact-form-leads-header mt-4 pb-2">
            <div>
                <?php if ($result) { ?>
                    <div>
                        <div class="alignleft actions bulkactions flex items-center">
                            <select name="action" id="bulk-action-selector-top">
                                <option value=""><?php esc_html_e('Bulk Actions', 'chaty') ?></option>
                                <option value="delete_message"><?php esc_html_e('Delete', 'chaty') ?></option>
                            </select>
                            <input type="submit" id="doaction" class="action btn cursor-pointer" value="<?php esc_html_e("Apply", "chaty") ?>" >
                        </div>
                    </div>
                <?php } ?>
            </div>

            <form class="flex items-center flex-wrap gap-3" action="<?php echo esc_url(admin_url("admin.php")) ?>" method="get">
                <label class="screen-reader-text" for="post-search-input"><?php esc_html_e("Search:", "chaty") ?></label>
                <select class="search-input mr-5" name="search_for" style="" id="date-range">
                    <?php foreach ($searchList as $key => $value) { ?>
                        <option <?php selected($key, $searchFor) ?> value="<?php echo esc_attr($key) ?>"><?php echo esc_attr($value) ?></option>
                    <?php } ?>
                </select>
                <input type="search" class="search-input" name="search" value="<?php echo esc_attr($search) ?>" class="">
                <input type="submit" class="cursor-pointer btn" id="search-submit" class="button" value="<?php esc_html_e("Search", "chaty") ?>">
                <input type="hidden" name="page" value="chaty-contact-form-feed" />
                <div class="date-range <?php echo ($searchFor == "custom" ? "active" : "") ?>">
                    <input type="search" class="search-input" name="start_date" id="start_date" value="<?php echo esc_attr($startDate) ?>" autocomplete="off" placeholder="Start date">
                    <input type="search" class="search-input" name="end_date" id="end_date" value="<?php echo esc_attr($endDate) ?>" autocomplete="off" placeholder="End date">
                </div>
            </form>
        </div>

        <?php }//end if
        ?>
        <form action="" method="post" class="responsive-table contact-form-lead">
            <?php if ($result) { ?>
            <table border="0" cellspacing="0" cellpadding="0" class="border-separate w-full rounded-lg border border-cht-gray-50 mb-5" id="contact-feed">
                <thead>
                    <tr>
                        <th class="rounded-tl-lg text-cht-gray-150 text-sm font-semibold font-primary py-3 px-2 bg-cht-primary-50" style="width:1%"><?php esc_html_e('Bulk', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('ID', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('Widget Name', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('Name', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('Email', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('Phone number', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('Message', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('IP Address', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('Date', 'chaty');?></th>
                        <th class="text-center text-cht-gray-150 text-sm font-semibold font-primary py-3 px-5 bg-cht-primary-50"><?php esc_html_e('URL', 'chaty');?></th>
                        <th class="rounded-tr-lg text-cht-gray-150 text-sm font-semibold font-primary py-3 px-2 bg-cht-primary-50"><?php esc_html_e('Delete', 'chaty');?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($result as $res) {
                        if ($res->widget_id == 0) {
                            $widgetName = "Default";
                        } else {
                            $widgetName = get_option("cht_widget_title_".$res->widget_id);
                            if (empty($widgetName)) {
                                $widgetName = "Widget #".($res->widget_id + 1);
                            }
                        }
                        $socialIcons = get_option('cht_social_Contact_Us');
                        ?>
                    <tr data-id="<?php echo esc_attr($res->id) ?>">
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r">
                            <div class="checkbox">
                                <label for="checkbox_<?php echo esc_attr($res->id) ?>" class="chaty-checkbox text-cht-gray-150 text-base items-center">
                                    <input class="sr-only" type="checkbox" id="checkbox_<?php echo esc_attr($res->id) ?>" name="chaty_leads[]" value="<?php echo esc_attr($res->id) ?>" />
                                    <span></span>
                                </label>
                            </div>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t" data-title="<?php esc_html_e('ID', 'chaty');?>">
                            <?php echo esc_attr($res->id) ?>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t" data-title="<?php esc_html_e('Widget Name', 'chaty');?>">
                            <?php echo esc_attr(stripslashes($widgetName)) ?>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t" data-title="<?php esc_html_e('Name', 'chaty');?>">
                            <?php echo esc_attr(stripslashes($res->name)) ?>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t" data-title="<?php esc_html_e('Email', 'chaty');?>">
                            <?php echo esc_attr(stripslashes($res->email)) ?>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t" data-title="<?php esc_html_e('Phone number', 'chaty');?>">
                            <?php echo esc_attr(stripslashes($res->phone_number)) ?>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t" data-title="<?php esc_html_e('Message', 'chaty');?>">
                            <?php echo nl2br(esc_attr(stripslashes($res->message))) ?>
                        </td>
                        <td
                            class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t"
                            data-title="<?php esc_html_e('IP Address', 'chaty');?>">
                            <?php
                            if(isset($socialIcons['capture_ip_address']) && $socialIcons['capture_ip_address'] == "yes") {
                                echo nl2br(esc_attr(stripslashes($res->ip_address)));
                            } else {
                                echo "";
                            }
                            ?>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t" data-title="<?php esc_html_e('Date', 'chaty');?>">
                            <?php echo esc_attr($res->created_on) ?>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center border-r border-t" data-title="<?php esc_html_e('URL', 'chaty');?>">
                            <a class="url" target="_blank" href="<?php echo esc_url($res->ref_page) ?>">
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </td>
                        <td class="bg-white py-3.5 px-5 text-cht-gray-150 font-primary text-sm text-center">
                            <a class="remove-record" href="#">
                                <span class="dashicons dashicons-trash"></span>
                            </a>
                        </td>
                    </tr>
                    <?php }//end foreach
                        ?>
                </tbody>
            </table>
                <?php
                if ($totalPages > 1) {
                    $baseURL = admin_url("admin.php?paged=%#%&page=chaty-contact-form-feed");
                    if (!empty($search)) {
                        $baseURL .= "&search=".esc_attr($search);
                    }

                    echo '<div class="custom-pagination">';
                    echo paginate_links(
                        [
                            'base'         => $baseURL,
                            'total'        => $totalPages,
                            'current'      => $current,
                            'format'       => '?paged=%#%',
                            'show_all'     => false,
                            'type'         => 'list',
                            'end_size'     => 3,
                            'mid_size'     => 1,
                            'prev_next'    => true,
                            'prev_text'    => sprintf('%1$s', '<span class="dashicons dashicons-arrow-left-alt2"></span>'),
                            'next_text'    => sprintf('%1$s', '<span class="dashicons dashicons-arrow-right-alt2"></span>'),
                            'add_args'     => false,
                            'add_fragment' => '',
                        ]
                    );
                    echo "</div>";
                }//end if
                ?>
            <div class="leads-buttons flex items-center gap-3 flex-wrap">
                <a href="<?php echo esc_url(admin_url("?download_chaty_file=chaty_contact_leads&nonce=".wp_create_nonce("download_chaty_contact_leads"))) ?>" class="btn rounded-lg inline-block" id="wpappp_export_to_csv" value="Export to CSV"><?php esc_html_e('Download & Export to CSV', 'chaty') ?></a>
                <input type="button" class="inline-block cursor-pointer rounded-lg bg-transparent border-red-500 text-red-500 hover:bg-red-500/10 focus:bg-red-500/10 hover:text-red-500 btn btn-primary" id="chaty_delete_all_leads" value="Delete All Data">
            </div>
            <?php  } else if (!empty($search) || $searchFor != "all_time") { ?>
            <div class="chaty-updates-form pt-7">
                <div class="testimonial-error-message max-w-screen-sm font-primary mx-auto">
                    <p class="px-5 text-2xl text-center"><?php esc_html_e('No records are found', 'chaty') ?></p>
                </div>
            </div>
            <?php } else { ?>
                <div class="container mt-12">
                    <div class="chaty-table no-widgets py-20 bg-cover rounded-lg border border-cht-gray-50">
                        <img class="mx-auto w-60" src="<?php echo esc_url(CHT_PLUGIN_URL . "/admin/assets/images/stars-image.png") ?>" />
                        <div class="text-center">
                            <div class="update-title text-cht-gray-150 text-3xl sm:text-4xl pb-5"><?php esc_html('Contact Form Leads', 'chaty') ?></div>
                            <p class="font-primary text-base text-cht-gray-150 -mt-2 max-w-screen-sm px-5 mx-auto">
                                <?php esc_html_e("Your contact form leads will appear here once you get some leads. Please make sure you've added the contact form channel to your Chaty channels in order to collect leads", 'chaty') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php }//end if
            ?>
            <input type="hidden" name="remove_chaty_leads" value="<?php echo esc_attr(wp_create_nonce("remove_chaty_leads")) ?>">
            <input type="hidden" name="paged" value="<?php echo esc_attr($current) ?>">
            <input type="hidden" name="search" value="<?php echo esc_attr($search) ?>">
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function() {
    var selectedURL = '<?php echo esc_url(admin_url())."admin.php?page=chaty-contact-form-feed&remove_chaty_leads=".wp_create_nonce("remove_chaty_leads")."&action=delete_message&paged={$current}&search=".esc_attr($search)."&chaty_leads=" ?>';
    jQuery(document).on("click", ".remove-record", function(e) {
        e.preventDefault();
        var redirectRemoveURL = selectedURL + jQuery(this).closest("tr").data("id");
        if (confirm("<?php esc_html_e("Are you sure you want to delete Record with ID# ", "chaty") ?>" + jQuery(this).closest("tr").data("id"))) {
            window.location = redirectRemoveURL;
        }
    });
    jQuery(document).on("click", "#doaction", function(e){
        if(jQuery("#bulk-action-selector-top").val() == "delete_message") {
            if(jQuery("#contact-feed input:checked").length) {

                var selectedIds = [];
                jQuery("#contact-feed input:checked").each(function(){
                    selectedIds.push(jQuery(this).val());
                });
                if(selectedIds.length) {
                    selectedIds = selectedIds.join(",");
                    var redirectRemoveURL = selectedURL+selectedIds;
                    if(confirm("<?php esc_html_e("Are you sure you want to delete selected records?", "chaty") ?>")) {
                        window.location = redirectRemoveURL;
                    }
                }
            }
        }
    });
    jQuery(document).on("click", "#chaty_delete_all_leads", function(e) {
        e.preventDefault();
        var redirectRemoveURL = selectedURL + "remove-all";
        if (confirm("<?php esc_html_e("Are you sure you want to delete all Record from the database?", "chaty") ?>")) {
            window.location = redirectRemoveURL;
        }
    });
    jQuery("#date-range").on("change", function() {
        if (jQuery(this).val() == "custom") {
            jQuery(".date-range").addClass("active");
        } else {
            jQuery(".date-range").removeClass("active");
        }
    });
    if (jQuery("#start_date").length) {
        jQuery("#start_date").datepicker({
            dateFormat: 'yy-mm-dd',
            altFormat: 'yy-mm-dd',
            maxDate: 0,
            onSelect: function(d, i) {
                var minDate = jQuery("#start_date").datepicker('getDate');
                minDate.setDate(minDate.getDate()); //add two days
                jQuery("#end_date").datepicker("option", "minDate", minDate);
                if (jQuery("#end_date").val() <= jQuery("#start_date").val()) {
                    jQuery("#end_date").val(jQuery("#start_date").val());
                }

                if (jQuery("#end_date").val() == "") {
                    jQuery("#end_date").val(jQuery("#start_date").val());
                }
            }
        });
    }
    if (jQuery("#end_date").length) {
        jQuery("#end_date").datepicker({
            dateFormat: 'yy-mm-dd',
            altFormat: 'yy-mm-dd',
            maxDate: 0,
            minDate: 0,
            onSelect: function(d, i) {
                if (jQuery("#start_date").val() == "") {
                    jQuery("#start_date").val(jQuery("#end_date").val());
                }
            }
        });
    }
});
</script>
