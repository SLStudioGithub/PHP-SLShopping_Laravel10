@section('footer')
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <span class="logo">SLショッピング</span>
                <p class="site">このECサイトは、課題演習用に作成したサイトです。</p>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <ul class="menu">
                    <span>Menu</span>
                    <li>
                        <a href="{{ route('shop.top') }}">Home</a>
                    </li>
                    <li>
                        <!-- TODO：配送料とかの紹介 -->
                        <a href="#">About</a>
                    </li>
                    <li>
                        <!-- TODO：ECサイトでは個人情報をどのように扱うかのページ -->
                        <a href="#">Privacy Policy</a>
                    </li>
                    <li>
                        <!-- ECサイトだと特定商取引表示が義務付けられているのでそのページ -->
                        <a href="{{ route('shop.commercial_transactions') }}">Commercial Transactions</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <ul class="address">
                    <span>Contact</span>
                    <li>
                        <p>Phone：<br><a href="tel:0123-456-789">0123-456-789</a></p>
                    </li>
                    <li>
                        <p>Address：<br>東京都千代田区丸の内１丁目</p>
                    </li>
                    <li>
                        <p>Email:<br><a href="mailto:sl-shopping-example@example.com?subject=SLショッピングお問い合わせ">sl-shopping-example@example.com</a></p>
                    </li>
                </ul>
            </div>
        </div>
        <p class="copyright"><small>Copyright © 2022 SLスタジオ All rights reserved.</small></p>
    </div>
</footer>
@endsection