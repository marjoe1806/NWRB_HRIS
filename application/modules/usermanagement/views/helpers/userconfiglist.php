<style>
    .pw_avatar{
        height: 90px;
        width: 90px;
        overflow: hidden;
        border-radius: 50%;
        margin: 0 auto 10px auto;
    }
    .pw_avatar img{
        display: block;
        height:90px;
        width:90px;
        max-width: 100%;
        height: auto;
    }
    .pw_info .name{
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: bold;
    }
    .pw_info .position{
        margin: 0px 0 2px 0;
        font-size: 13px;
        color: #678ec4;
    }
    .pw_info .position small{
        font-size: 12px;
    }
    /* .pw_info p{
        margin: 0;
        font-size: 12px;
    } */
    .user-container {
        display:block;
        height:180px;
        /* height: 153px; */
        overflow: hidden;
        text-align: center;
        position: relative;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        transition:All 0.4143s ease;
        -webkit-transition:All 0.4143s ease;
        -moz-transition:All 0.4143s ease;
        -o-transition:All 0.4143s ease;
        transform: rotate(0deg) scale(0.799) skew(1deg) translate(0px);
        -webkit-transform: rotate(0deg) scale(0.799) skew(1deg) translate(0px);
        -moz-transform: rotate(0deg) scale(0.799) skew(1deg) translate(0px);
        -o-transform: rotate(0deg) scale(0.799) skew(1deg) translate(0px);
        -ms-transform: rotate(0deg) scale(0.799) skew(1deg) translate(0px);
    }
    .user-container:active,
    .user-container:hover{
        overflow: visible;
        z-index:1;
        transform: rotate(0deg) scale(1) skew(1deg) translate(0px);
        -webkit-transform: rotate(0deg) scale(1) skew(1deg) translate(0px);
        -moz-transform: rotate(0deg) scale(1) skew(1deg) translate(0px);
        -o-transform: rotate(0deg) scale(1) skew(1deg) translate(0px);
        -ms-transform: rotate(0deg) scale(1) skew(1deg) translate(0px);
    }
</style>
<div class="listTable">
    <div class="row">
        <?php 
        if(isset($list->Data->details) && sizeof($list->Data->details) > 0){ 
            foreach ($list->Data->details as $index => $value) { ?>
                <a class="updateUserConfigForm" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>updateUserConfigForm" style="color:#3b3b3b;text-decoration: none !important;"
                    data-id="<?php echo $value->userid; ?>"
                    <?php 
                        foreach ($value as $k => $v) {
                            echo ' data-'.$k.'="'.$v.'" ';
                        } 
                    ?>
                >
                    <div class="col-md-2 col-sm-4 col-xs-6 text-center user-container">
                        <div class="pw_avatar waves-effect">
                            <?php 
                                // $photo = base_url()."assets/custom/images/account_circle_grey_192x192.png";
                                $photo = $value->photopath;
                                if($value->photopath == "n/a" || $value->photopath == "N/A" || $value->photopath == "")
                                    $photo = base_url()."assets/custom/images/account_circle_grey_192x192.png";
                             ?>
                            <img src="<?php echo $photo; ?>" alt="User">
                        </div>
                        <div class="pw_info">
                            <?php
                                $textClass = "";
                                ($value->status == "ACTIVE")?$textClass = "text-success":$textClass = "text-danger";
                            ?>
                            <p class="name"><?php echo $value->first_name.' '.$value->last_name; ?></p>
                            <p class="position"><?php echo $value->position_name; ?>
                                <small class="<?php echo $textClass; ?>"><br><?php echo $value->status; ?></small>
                            </p>
                        </div>
                    </div>
                </a>
        <?php }
        }
        else{ 
        ?>
        <div style="width:100%" class="text-center"><span>No data available.</span></div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <?php //echo $pagination; ?>
        </div>
    </div>
</div>