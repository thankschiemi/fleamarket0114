@component('mail::message')
# 取引完了のご連絡

出品中の商品 **「{{ $purchase->product->name }}」** の取引が
購入者 **{{ $purchase->user->name }}** さんにより **完了** しました。

@component('mail::panel')
価格：￥{{ number_format($purchase->product->price) }}
完了日時：{{ optional($purchase->updated_at)->format('Y-m-d H:i') }}
@endcomponent

@component('mail::button', ['url' => route('mypage', ['tab' => 'trade'])])
取引ページを開く
@endcomponent

今後ともよろしくお願いいたします。
{{ config('app.name') }}
@endcomponent