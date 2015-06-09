
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Update Installation
                    </header>
                    <div class="panel-body">
                        {foreach $install as $key=>$item}
                        <form class="form-horizontal" id="default" action="index.php?controller=installation&action=update&id={$item['objectId']}" method="post">


                            <div class="form-group">
                                <label class="col-lg-2 control-label"> appIdentifier:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="appIdentifier" id="appIdentifier" class="form-control" value="{$item['appIdentifier']}" placeholder="appIdentifier">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">timeZone:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="timeZone" id="timeZone" class="form-control" value="{$item['timeZone']}" placeholder="timeZone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">deviceName:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="deviceName" id="deviceName" value="{$item['deviceName']}" class="form-control" placeholder="deviceName">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">appName</label>
                                <div class="col-lg-10">
                                    <input type="text" name="appName" id="appName" value="{$item['appName']}" class="form-control" placeholder="appName">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">appVersion</label>
                                <div class="col-lg-10">
                                    <input type="text" name="appVersion" id="appVersion" value="{$item['appVersion']}" class="form-control" placeholder="appVersion">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">parseVersion</label>
                                <div class="col-lg-10">
                                    <input type="text" name="parseVersion" id="parseVersion" value="{$item['parseVersion']}" class="form-control" placeholder="parseVersion">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">deviceTokenLastModified</label>
                                <div class="col-lg-10">
                                    <input type="text" name="deviceTokenLastModified" id="deviceTokenLastModified" value="{$item['deviceTokenLastModified']}" class="form-control" placeholder="deviceTokenLastModified">
                                </div>
                            </div>

                            <input type="submit" name="update" id="update" class="finish btn btn-danger" value="Update"/>
                            {/foreach}
                        </form>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>