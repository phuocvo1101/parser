<script type="text/javascript">
    function answers()
    {
        var selectedanswer=document.getElementById("recordlimit").value;
        var frm = document.getElementById("frm");
        frm.action = "index.php?controller=report&action=index&limit="+selectedanswer;
        frm.submit();
    }
</script>
<form id="frm" action="index.php?controller=account&action=index" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">

                        <div class="panel-body">
                            <form role="form" class="form-horizontal tasi-form">
                                Account View
                            </form>

                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" id="settingForm" method="post" action="index.php?controller=account&action=index" novalidate="novalidate">
                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">Name</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="{if !empty($account->name)}{$account->name}{/if}" id="account_name" name="account_name"  type="text" required="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">Type</label>
                                        <div class="col-lg-10">
                                            <input class="form-control " value="{if !empty($account->type)}{$account->type}{/if}" id="account_type" type="text" name="account_type" required="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="curl" class="control-label col-lg-2">Status</label>
                                        <div class="col-lg-10">
                                            <input class="form-control " value="{if !empty($account->status)}{if $account->status==1}Actived{else}InActived{/if}{else}InActived{/if}" id="account_status" type="text" name="account_status">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">Created Date</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="{if !empty($account->created_day)}{$account->created_day|date_format:"%Y-%m-%d %H:%M:%S"}{/if}" id="account_created_date" name="account_created_date"  type="text" required="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">Modified Date</label>
                                        <div class="col-lg-10">
                                            <input class="form-control " value="{if !empty($account->modified_day)}{$account->modified_day|date_format:"%Y-%m-%d %H:%M:%S"}{/if}" id="account_modified_date" type="text" name="account_modified_date" required="">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <button class="btn btn-default" onclick="window.location='index.php?controller=account&action=index'" type="button">Back</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </section>
                </div>
            </div>

            <!-- page end-->
        </section>
    </section>
</form>