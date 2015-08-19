<div class="navbar md-whiteframe-z1 no-radius blue">
    <!-- Open side - Naviation on mobile -->
    <a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
    <!-- / -->
    <!-- Page title - Bind to $state's title -->
    <div class="navbar-item pull-left h4">{{ $pageTitle or '' }}</div>
    <!-- / -->
    <!-- Common tools -->
    <div class="pull-right" ui-view="navbar@"></div>
    <!-- / -->
    <!-- Search form -->
    <div id="search" class="pos-abt w-full h-full indigo hide">
        <div class="box">
            <div class="box-col w-56 text-center">
                <!-- hide search form -->
                <a md-ink-ripple class="navbar-item inline"  ui-toggle-class="show" target="#search"><i class="mdi-navigation-arrow-back i-24"></i></a>
            </div>
            <div class="box-col v-m">
                <!-- bind to app.search.content -->
                <input class="form-control input-lg no-bg no-border" placeholder="Search" ng-model="app.search.content">
            </div>
            <div class="box-col w-56 text-center">
                <a md-ink-ripple class="navbar-item inline"><i class="mdi-av-mic i-24"></i></a>
            </div>
        </div>
    </div>
    <!-- / -->
</div>