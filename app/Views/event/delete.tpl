
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Delete Events
                    </header>
                    <div class="panel-body">
                        {foreach $event as $key=>$item}
                            <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=event&action=delete&id={$item['objectId']}" method="post">


                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="Title" id="Title" value="{$item['Title']}" class="form-control" readonly="readonly"  placeholder="Title">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Content:</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="Content" id="Content" value="{$item['Content']}" class="form-control" readonly="readonly"  placeholder="Content">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">EventDate:</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="EventDate" id="EventDate" value="{$item['EventDate']}" class="form-control" readonly="readonly"  placeholder="EventDate">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">EventYear:</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="EventYear" id="EventYear" value="{$item['EventYear']}" class="form-control" readonly="readonly"  placeholder="EventYear">
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