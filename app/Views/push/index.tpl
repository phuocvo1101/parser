
<form id="frm" action="index.php?controller=push&action=index" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">

                        <div class="row">
                            <div class="col-xs-6 col-sm-6 placeholder">
                                <h3 class="page-header" align="left"><span> Push Notifications</span></h3>
                            </div>
                            <div class="col-sm-4"></div>

                            <div class=" col-xs-6 col-sm-2">
                                <div>
                                    <input type="submit" id="submit" name="submit" class="btn btn-primary" value="send a push">

                                </div>
                            </div>

                        </div>

                        <div class="panel-body">
                            <section id="unseen">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <td align="center">Status </td>
                                        <td>Target</td>
                                        <td>Name </td>
                                        <td>Time </td>



                                    </tr>
                                    </thead>
                                    <tbody>
                                    {if isset($message)}
                                        {foreach $message as $key=>$item}
                                            <tr>
                                                <td align="center">
                                                <input type="checkbox" {if $item->status==1}checked="checked"{/if} />
                                                </td>
                                                <td>{$item->target}</td>
                                                <td>{$item->name}</td>
                                                <td>{$item->time|date_format:"%Y-%m-%d %H:%M:%S"}</td>


                                            </tr>
                                        {/foreach}
                                    {/if}

                                    </tbody>
                                </table>
                            </section>
                        </div>
                    </section>
                </div>
            </div>

            <!-- page end-->
        </section>
    </section>
</form>