<form id="frm" action="index.php?controller=systemuser&action=index" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">

                        <div class="panel-body">
                            <form role="form" class="form-horizontal tasi-form">
                                User View
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
                                <form class="cmxform form-horizontal tasi-form" id="settingForm" method="post" action="index.php?controller=systemuser&action=index" novalidate="novalidate">
                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">UserName</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" value="{if !empty($user->username)}{$user->username}{/if}" id="username" name="username"  type="text" required="">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="curl" class="control-label col-lg-2">Status</label>
                                        <div class="col-lg-10">
                                            <input class="form-control " value="{if !empty($user->status)}{if $user->status==1}Actived{else}InActived{/if}{else}InActived{/if}" id="status" type="text" name="status">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">Account Name</label>
                                        <div class="col-lg-10">
                                            <input class="form-control " value="{if !empty($user->account_name)}{$user->account_name}{/if}" id="account_name" type="text" name="account_name" required="">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">Email</label>
                                        <div class="col-lg-10">
                                            <input class="form-control " value="{if !empty($user->email)}{$user->email}{/if}" id="email" type="text" name="email" required="">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">Full Name</label>
                                        <div class="col-lg-10">
                                            <input class="form-control " value="{if !empty($user->fullname)}{$user->fullname}{/if}" id="fullname" type="text" name="fullname" required="">
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
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