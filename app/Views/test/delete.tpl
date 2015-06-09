
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Update test
                    </header>
                    <div class="panel-body">
                        {foreach $test as $key=>$item}
                        <form class="form-horizontal" id="default" action="index.php?controller=test&action=delete&id={$item['objectId']}" method="post">


                            <div class="form-group">
                                <label class="col-lg-2 control-label"> mode:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="mode" id="mode" class="form-control" value="{$item['mode']}" readonly="readonly" placeholder="mode">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">name:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="name" id="name" class="form-control" value="{$item['mode']}" readonly="readonly" placeholder="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">phone:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="phone" id="phone" value="{$item['phone']}" readonly="readonly" class="form-control" placeholder="phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">score</label>
                                <div class="col-lg-10">
                                    <input type="text" name="score" id="score" value="{$item['score']}" readonly="readonly" class="form-control" placeholder="score">
                                </div>
                            </div>

                            <input type="submit" name="delete" id="delete" class="finish btn btn-danger" value="Delete"/>
                            {/foreach}
                        </form>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>