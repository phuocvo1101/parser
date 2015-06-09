
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Delete DayTitle
                    </header>
                    <div class="panel-body">
                        {foreach $dayTitle as $key=>$im}

                            <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=dayTitle&action=delete&id={$im['objectId']}" method="post">


                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="Title" id="Title" value="{$im['Title']}" readonly="readonly" class="form-control"  placeholder="Title">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 control-label">TitleDate</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="TitleDate" id="TitleDate" value="{$im['TitleDate']}" readonly="readonly"  class="form-control" placeholder="TitleDate">
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