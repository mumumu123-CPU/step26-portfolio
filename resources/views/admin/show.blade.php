<x-app-layout>
    <!--　固定ヘッダー -->
    <div class="w-full fixed top-0 left-0 z-50 px-8 py-4 bg-white shadow-md flex justify-between items-center">
        <a href="{{ route('admin.hospitals.index') }}"
            class="text-base font-bold text-gray-800 hover:underline">精神科評価サイト</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="text-base font-bold text-gray-800 hover:underline">管理者ログアウト</button>
        </form>
    </div>


    <div class="py-32 text-center border-b border-gray-400 bg-sky-400 bg-opacity-30 rounded-lg">
        <p class="text-[40px] text-gray-800 font-semibold">病院の詳細情報</p>
    </div>


    <div class="bg-sky-400 bg-opacity-30 min-h-screen py-10 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-transparent shadow rounded-lg p-6 mb-6">
                <div class="flex lg:flex-row gap-6">
                    <!-- 左側 -->
                    <div class="lg:w-1/2 space-y-10">
                        <!--編集・削除ボタン-->
                        <div class="flex gap-4">
                            <a href="{{ route('admin.hospitals.edit', $hospital->id) }}"
                                class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded">✏
                                編集</a>
                            <form method="POST" action="{{ route('admin.hospitals.destroy', $hospital->id) }}"
                                onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">🗑
                                    削除</button>
                            </form>
                        </div>
                        <h1 class="text-[32px] font-bold text-center text-gray-800 my-6">{{ $hospital->name }}</h1>
                        <!--ダミー画像。画像のサイズを固定。はみ出た部分は表示しない。-->
                        <div
                            class="bg-gray-200 w-[500px] h-[400px] flex items-center justify-center mx-auto overflow-hidden rounded">
                            <img src="https://picsum.photos/seed/{{ uniqid() }}/{{ rand(400, 800) }}/{{ rand(300, 600) }}"
                                class="object-cover w-full h-full" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold mb-4 text-center">診療時間</p>
                            <table class="table-auto w-[500px] text-center border border-gray-400 mx-auto">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th></th>
                                        @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
                                            <th>{{ $day }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $hospital->am_open }}</td>
                                        @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
                                            <td>{{ str_contains($hospital->day_of_week, $day) ? '●' : '-' }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td>{{ $hospital->pm_open }}</td>
                                        @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
                                            <td>{{ str_contains($hospital->day_of_week, $day) ? '●' : '-' }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-gray-200 w-[500px] h-[400px] flex items-center justify-center mx-auto">
                            <iframe class=""
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7133.418955402901!2d139.76447358931546!3d35.68110313679508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1748863094072!5m2!1sja!2sjp"
                                width="500" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <!-- 右側。ここの箇所のみ8の倍数以外を使用しています。８の倍数だと大きすぎたり、小さすぎたりといい塩梅のサイズ感にならなかったためです。 -->
                    <div class="lg:w-1/2 space-y-6 text-base text-gray-700 mt-16">
                        <p class="text-lg"><span class="font-semibold text-lg">病院名：</span>{{ $hospital->name }}</p>
                        <p class="text-lg"><span class="font-semibold text-lg">所在地：</span>{{ $hospital->address }}</p>
                        <p class="text-lg"><span class="font-semibold text-lg">最寄駅：</span>{{ $hospital->station }}</p>
                        <p class="text-lg"><span class="font-semibold text-lg">電話番号：</span>{{ $hospital->phone }}</p>
                        <p class="text-lg"><span class="font-semibold text-lg">HP：</span>{{ $hospital->homepage_url }}
                        </p>
                        <p class="text-lg"><span
                                class="font-semibold text-lg">専門外来：</span>{{ $hospital->specialties->pluck('name')->join('、') }}
                        </p>
                        <p class="text-lg"><span
                                class="font-semibold text-lg">対象疾患：</span>{{ $hospital->disorders->pluck('name')->join('、') }}
                        </p>
                        <p class="text-lg"><span class="font-semibold text-lg">治療法：</span>{{ $hospital->treatment }}
                        </p>
                        <p class="text-lg"><span class="font-semibold text-lg">特徴：</span>{{ $hospital->feature }}</p>
                        <p class="text-lg"><span
                                class="font-semibold text-lg">口コミ平均：</span>★{{ number_format($hospital->reviews->avg('rating'), 1) }}（{{ $hospital->reviews->count() }}件）
                        </p>

                        @foreach ($hospital->reviews as $review)
                            <div class="bg-gray-100 border border-gray-300 p-2 rounded">
                                <p>★{{ $review->rating }}</p>
                                <p>{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white text-center py-6">
        <a href="{{ route('admin.hospitals.index') }}" class="text-base tetext-gray-800 font-bold hover:underline">
            精神科評価サイト
        </a>
    </footer>
</x-app-layout>

<!--
   <div class="flex gap-4">
                            <a href="{{ route('admin.hospitals.edit', $hospital->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded">✏ 編集</a>
                            <form method="POST" action="{{ route('admin.hospitals.destroy', $hospital->id) }}" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">🗑 削除</button>
                            </form>
                        </div>
-->




<!--
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者用病院詳細ページ</title>
    <style>
        body { font-family: sans-serif; background: #e0edff; }
        .header, .footer {
            background: #b0d0ff; padding: 10px; text-align: center;
            font-weight: bold;
        }
        .container {
            width: 90%; max-width: 1000px; margin: 20px auto; background: #fff; padding: 20px;
        }
        h2 { text-align: center; background: #ddd; padding: 10px; margin-bottom: 20px; }
        .info-section { display: flex; justify-content: space-between; }
        .left, .right { width: 48%; }
        .img-box, .map-box {
            width: 100%; height: 150px; background: #eee; display: flex; align-items: center; justify-content: center;
            margin-bottom: 15px;
        }
        .btns { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
        .btns a {
            text-align: center; padding: 10px; border-radius: 4px; text-decoration: none;
        }
        .btn-edit { background: #ffd700; color: #000; }
        .btn-delete {
                background: #ff8080;
                color: white;
                padding: 10px;
                border-radius: 4px;
                text-align: center;
                display: block;
                text-decoration: none;
                width: 100%;
        }

        .label { font-weight: bold; }
        .review { background: #f6f6f6; margin: 5px 0; padding: 5px; border: 1px solid #ccc; }

    </style>
</head>
<body>

<div class="header">精神科評価サイト｜管理者ログアウト</div>

<div class="container">
    <h2>病院の詳細情報</h2>

    <div class="btns">
        <a href="{{ route('admin.hospitals.edit', $hospital->id) }}" class="btn-edit">✏️ 編集</a>
        <form method="POST" action="{{ route('admin.hospitals.destroy', $hospital->id) }}"
            onsubmit="return confirm('本当に削除しますか？');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-delete">🗑️ 削除</button>
      </form>
        </form>
    </div>

    <div class="info-section">
        <div class="left">
            <div class="img-box">病院の画像</div>

            <div class="label">診療時間</div>
                <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; text-align: center;">
                    <tr style="background-color: #e0f0ff;">
                        <th></th>
                        @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
<th>{{ $day }}</th>
@endforeach
                    </tr>
                    <tr>
                        <td>{{ $hospital->am_open }}</td>
                        @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
<td>{{ str_contains($hospital->day_of_week, $day) ? '●' : '-' }}</td>
@endforeach
                    </tr>
                    <tr>
                        <td>{{ $hospital->pm_open }}</td>
                        @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
<td>{{ str_contains($hospital->day_of_week, $day) ? '●' : '-' }}</td>
@endforeach
                    </tr>
                </table>

            <div class="map-box">地図</div>
        </div>

        <div class="right">
            <p><span class="label">病院名：</span> {{ $hospital->name }}</p>
            <p><span class="label">所在地：</span> {{ $hospital->address }}</p>
            <p><span class="label">最寄駅：</span> {{ $hospital->station }}</p>
            <p><span class="label">電話番号：</span> {{ $hospital->phone }}</p>
            <p><span class="label">HP：</span> {{ $hospital->homepage_url }}</p>
            <p><span class="label">専門外来：</span> {{ $hospital->specialties->pluck('name')->join('、') }}</p>
            <p><span class="label">対象疾患：</span> {{ $hospital->disorders->pluck('name')->join('、') }}</p>
            <p><span class="label">治療法：</span> {{ $hospital->treatment }}</p>
            <p><span class="label">特徴：</span> {{ $hospital->feature }}</p>
            <p><span class="label">口コミ平均：</span> ★{{ number_format($hospital->reviews->avg('rating'), 1) }}（{{ $hospital->reviews->count() }}件）</p>

            @foreach ($hospital->reviews as $review)
<div class="review">★{{ $review->rating }}<br>{{ $review->comment }}</div>
@endforeach
        </div>
    </div>
</div>

<div class="footer">精神科評価サイト</div>

</body>
</html>
-->
