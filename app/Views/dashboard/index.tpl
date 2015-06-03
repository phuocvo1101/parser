<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="row">
        <div class="col-mod-12">

            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li>Dashboard</li>
            </ul>

            <h3 class="page-header"><i class="fa fa fa-dashboard"></i> Dashboard  </h3>
        </div>
    </div>


    <!-- Info Boxes -->
    <div class="row">
        <div class="col-md-4">
            <div class="info-box  bg-info  text-white">
                <div class="info-icon bg-info-dark">
                    <span class="glyphicon glyphicon-eye-open fa-4x" aria-hidden="true"></span>
                    <span class="pull-right"><h1>{if isset($totalreport)}{$totalreport}{/if}</h1></span>
                </div>
                <div class="info-details">
                    <h4>Report RECEIVED</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4 ">
            <div id="initial-tour" class="info-box  bg-success  text-white">
                <div class="info-icon bg-success-dark">
                    <span class="glyphicon glyphicon-eye-close fa-4x"></span>
                    <span class="pull-right"><h1>{if isset($totalproduct)}{$totalproduct}{/if}</h1></span>
                </div>
                <div class="info-details">
                    <h4>PRODUCTS  </h4>

                </div>
            </div>
        </div>

    </div>
</div>