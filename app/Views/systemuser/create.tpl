
<form id="frm" action="index.php?controller=systemuser&action=create" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">

                        <div class="panel-body">
                            <form role="form" class="form-horizontal tasi-form">
                                Create Account
                            </form>
                            {if !empty($message) && $message!=''}
                                <div style="color:red;">{$message}</div>
                            {/if}
                            {if !empty($result)}
                                {if $result==0}
                                    <div style="color:red;">Create account was failed</div>
                                {else}
                                    <div style="color:green;">Create account was successfull</div>
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
                                <form class="cmxform form-horizontal tasi-form" id="settingForm" method="post" action="index.php?controller=systemuser&action=create" novalidate="novalidate">
                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">UserName</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="{if !empty($user->username)}{$user->username}{/if}" id="username" name="username"  type="text" required="">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">Password</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="" id="password" name="password"  type="password" required="">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">Confirm-Password</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="" id="confirm_password" name="confirm_password"  type="password" required="">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">Account</label>
                                        <div class="col-lg-10">
                                            <select name="account_id" id="account_id" class="form-control input-lg m-bot15">
                                                {foreach $listAccount as $item}
                                                    <option {if !empty($user->account_id) && $user->account_id==$item->id}selected="selected"{/if} value="{$item->id}">{$item->name}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">Email</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="{if !empty($user->email)}{$user->email}{/if}" id="email" name="email"  type="text" required="">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">Full Name</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="{if !empty($user->fullname)}{$user->fullname}{/if}" id="fullname" name="fullname"  type="text" required="">
                                        </div>
                                    </div>




                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <button class="btn btn-danger" name="subFormUser" id="subFormUser" type="submit">Save</button>
                                            <button class="btn btn-default" onclick="window.location='index.php?controller=systemuser&action=index'" type="button">Back</button>

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