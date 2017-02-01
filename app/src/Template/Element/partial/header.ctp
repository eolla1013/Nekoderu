<style>
.navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.open>a {
    background-image: none;
    box-shadow: none;
}
.navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:hover {
    color: white;
    background-color: inherit;
}

</style>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">ねこがいますか？</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-navbar">
            <ul class="nav navbar-nav">
                <li>
                    <a href="/cats/photoGrid">猫フォト</a>
                </li>
                <li class="dropdown active">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">ご協力ください <sup>New!</sup> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/games/similarity">似ているねこを判定</a></li>
					</ul>
				</li>
                <li class="dropdown active">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">迷子をさがす <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/cats/addLost">迷子猫を登録</a></li>
						<li><a href="/cats/tag/迷子猫探してます">迷子猫の一覧</a></li>
					</ul>
				</li>
                <li class="dropdown active">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">お問い合わせ <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/policy/contact">お問い合わせ</a></li>
						<li><a href="/pages/top">これはなに？</a></li>
						<li><a href="/policy/index">利用規約</a></li>
					</ul>
				</li>
            </ul>
            <?php if (!isset($auth)): ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/login">ログイン</a></li>
                </ul>
            <?php else: ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/profiles/user/<?=$auth['username']?>">マイページ</a></li>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            <?php endif; ?>
            
        </div>
        <!-- /.navbaｃr-collapse -->
        
    </div>
    <!-- /.container -->
</nav>
