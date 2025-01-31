@extends('layouts.app')

@section('content')
<div class="container">
    <h2>コンビニ支払いの手続き</h2>
    <p>お支払いの準備が整いました。以下の手順に従って、コンビニでお支払いください。</p>
    <ol>
        <li>指定されたコンビニで「代金支払い」メニューを選択します。</li>
        <li>受付番号を入力して支払いを行ってください。</li>
        <li>お支払いが完了すると、システムが自動的に更新されます。</li>
    </ol>
    <p><strong>⚠️ まだ購入は完了していません。</strong> コンビニで支払い完了後、ステータスが更新されます。</p>
</div>
@endsection