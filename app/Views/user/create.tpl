
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Create User
                    </header>
                    <div class="panel-body">

                        <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=user&action=create" method="post">
                            {if isset($mss)}
                                {foreach $mss as $key=>$item}
                            <label style="color: red; text-align: center"  class="col-lg-12 control-label">{$item}</label>
                                {/foreach}
                            {/if}

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Username:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="username" id="username" class="form-control"  placeholder="Title">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Password:</label>
                                <div class="col-lg-10">
                                    <input type="password" name="password" id="password" class="form-control"  placeholder="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">RePassword:</label>
                                <div class="col-lg-10">
                                    <input type="password" name="repassword" id="repassword" class="form-control"  placeholder="repassword">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Email</label>
                                <div class="col-lg-10">
                                    <input type="text" name="email" id="email"  class="form-control" placeholder="email">
                                </div>
                            </div>


                            <input type="submit" name="create" id="create" class="finish btn btn-danger" value="Create"/>
                        </form>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>