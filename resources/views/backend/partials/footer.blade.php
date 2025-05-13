<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">

                © {{ $date->format('Y') }}


            </div>
            <div class="col-sm-6 text-sm-end d-none d-sm-block">
                {{ $website_settings->admin_footer ?? 'Default Title' }} </div>
        </div>
    </div>
</footer>
