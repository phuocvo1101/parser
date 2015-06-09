
<form id="frm" action="index.php?controller=systemuser&action=edit" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">

                        <div class="panel-body">
                            <form role="form" class="form-horizontal tasi-form">
                                Edit User
                            </form>
                            {if !empty($message) && $message!=''}
                                <div style="color:red;">{$message}</div>
                            {/if}
                            {if !empty($result)}
                                {if $result==0}
                                    <div style="color:red;">Update user was failed</div>
                                {else}
                                    <div style="color:green;">Update user was successfull</div>
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
                                <form class="cmxform form-horizontal tasi-form" id="settingForm" method="post" action="index.php?controller=systemuser&action=edit" novalidate="novalidate">
                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">UserName</label>
                                        <div class="col-lg-10">
                                            <input disabled="disabled" class=" form-control" value="{if !empty($user->username)}{$user->username}{/if}" id="username" name="username"  type="text" required="">
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
                                            <input type="hidden" value="{$user->id}" id="id" name="id"/>
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