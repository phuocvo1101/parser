
<form id="frm" action="index.php?controller=account&action=edit" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">

                        <div class="panel-body">
                            <form role="form" class="form-horizontal tasi-form">
                                Edit Account
                            </form>
                            {if !empty($message) && $message!=''}
                                <div style="color:red;">{$message}</div>
                            {/if}
                            {if !empty($result)}
                                {if $result==0}
                                    <div style="color:red;">Update account was failed</div>
                                {else}
                                    <div style="color:green;">Update account was successfull</div>
                                {/if}
                            {/if}
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
                                <form class="cmxform form-horizontal tasi-form" id="settingForm" method="post" action="index.php?controller=account&action=edit" novalidate="novalidate">
                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">Name</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="{if !empty($account->name)}{$account->name}{/if}" id="account_name" name="account_name"  type="text" required="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">Type</label>
                                        <div class="col-lg-10">
                                            <select name="account_type" id="account_type" class="form-control input-lg m-bot15">
                                                <option {if !empty($account->type) && $account->type=="admin"}selected="selected"{/if}  value="admin">Admin</option>
                                                <option {if !empty($account->type) && $account->type=="agent"}selected="selected"{/if} value="agent">Agent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="curl" class="control-label col-lg-2">Status</label>
                                        <div class="col-lg-10">
                                            <div class="col-lg-10">
                                                <select disabled="disabled" name="account_status" id="account_status" class="form-control input-lg m-bot15">
                                                    <option {if $account->status=="1"}selected="selected"{/if}  value="1">Active</option>
                                                    <option  {if $account->status=="0"}selected="selected"{/if}  value="0">InActive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <button class="btn btn-danger" name="subFormAccount" id="subFormAccount" type="submit">Save</button>
                                            <button class="btn btn-default" onclick="window.location='index.php?controller=account&action=index'" type="button">Back</button>
                                            <input type="hidden" value="{$account->id}" id="id" name="id"/>
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