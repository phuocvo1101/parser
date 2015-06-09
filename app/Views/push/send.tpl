<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('#choose').change(function(){
           if($(this).val()=='s') {
                $('#channel').css('display','');
           } else{
               $('#channel').css('display','none');
           }
        });
    });

</script>
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Send a push notification
                    </header>
                    <div class="panel-body">

                        <form class="form-horizontal" id="default" action="index.php?controller=push&action=send" method="post">

                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Choose Your Recipients</label>
                                    <div class="col-lg-6">
                                        <select id="choose" class="form-control" name="choose">
                                            <option {if isset($data->target)&& $data->target=="everyone"} selected="selected"{/if} value="e">Everyone</option>
                                            <option {if isset($data->target)&& $data->target!="everyone"} selected="selected"{/if} value="s">Segment</option>
                                        </select>

                                    </div>

                                </div>
                                <div style="display:none;" id="channel" class="form-group">
                                    <label class="col-lg-2 control-label">Channels</label>
                                    <div class="col-lg-6">
                                        <input type="text" name="chan" id="chan" class="form-control" value="{if isset($data->target)&& $data->target!="everyone"}{$data->target}{/if}" placeholder="Channels">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Write Your Message</label>
                                    <div class="col-lg-6">
                                        <textarea  class="form-control"  name="message" id="message"  >{if isset($data->name)}{$data->name}{/if}</textarea>

                                    </div>
                                </div>



                            <input type="submit" name="sendmess" id="sendmess" class="finish btn btn-danger" value="Send now"/>
                        </form>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>