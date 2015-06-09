
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Delete User
                    </header>
                    <div class="panel-body">
                        {foreach $user as $key=>$im}
                            <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=user&action=delete&id={$im['objectId']}" method="post">

                                {if isset($mss)}
                                    {foreach $mss as $key=>$item}
                                        <label style="color: red; text-align: center"  class="col-lg-12 control-label">{$item}</label>
                                    {/foreach}
                                {/if}
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Username:</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="username" id="username" value="{$im['username']}" readonly="readonly" class="form-control"  placeholder="Title">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Email</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="email" id="email" value="{$im['email']}" readonly="readonly"  class="form-control" placeholder="email">
                                    </div>
                                </div>


                                <input type="submit" name="delete" id="delete" class="finish btn btn-danger" value="Delete"/>
                            </form>
                        {/foreach}
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>