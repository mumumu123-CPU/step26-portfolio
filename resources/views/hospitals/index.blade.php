<x-app-layout>
    <!--　固定ヘッダー -->
    <div class="w-full fixed top-0 left-0 z-50 px-8 py-4 bg-white shadow-md flex justify-between items-center">
        <a href="{{ route('hospital.index') }}" class="text-base text-gray-800 font-bold hover:underline">
            精神科評価サイト
        </a>
        <a href="{{ route('admin.login.form') }}" class="text-base text-gray-800 font-bold hover:underline">
            管理者ログイン
        </a>
    </div>

    <!--　ヒーロー　-->
    <section class="relative min-h-screen text-white overflow-hidden">
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
            <source src="{{ asset('assets/videos/hero3.mp4') }}" type="video/mp4">
        </video>
        <!-- 中央のテキスト -->
        <div class="relative z-10 flex flex-col items-center justify-start text-center px-4 py-24">
            <p class="text-[40px] font-bold mb-2 font-sans">あなたに合った精神科を</p>
            <h1 class="text-6xl font-semibold mb-2">精神科評価サイト</h1>

        </div>

    </section>

    <div class="px-4 py-16 bg-brand-100 bg-opacity-30 p-6 rounded-lg">
        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-x-24 items-center md:items-start py-16 my-6 mx-auto max-w-screen-xl">

            <!-- 左：画像 -->
            <div>
                <img src="{{ asset('assets/images/image5.jpg') }}" alt="相談中の様子"
                    class="rounded-xl w-full h-auto object-cover">
            </div>

            <!-- 右：テキスト -->
            <div class="text-gray-800 space-y-7 leading-loose text-2xl">

                <h2 class="text-[32px] font-semibold tracking-wide">
                    精神的な不調を感じたとき、<br>
                    「どの病院が自分に合うんだろう…？」<br>
                    と悩む方は多いはず。
                </h2>

                <p class="font-normal">
                    このサイトは、専門外来・対象疾患・地域から、<br>
                    あなたに合った精神科・心療内科をスムーズに探せる検索サービスです。
                </p>

                <p class="font-normal">
                    「どこに相談すればいいか分からない」<br>
                    そんな不安を減らし、あなたが安心して一歩を踏み出せるようにサポートします。
                </p>

                <p class="font-normal">
                    口コミや診療時間など、<br>
                    気になる情報もひと目で確認できます。
                </p>

            </div>

        </div>
    </div>

    <!-- 特徴セクション全体を包むグリーン背景 -->
    <div class="bg-brand-100 bg-opacity-30 py-20 px-4 border-b border-gray-300">

        <!-- 白枠カード部分 -->
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-7xl mx-auto">
            <h3 class="text-[32px] font-semibold text-gray-800 mb-6 text-center">このサイトの特徴</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700 text-center">

                <!-- カード1 -->
                <div class="p-4 border rounded-lg hover:shadow transition">
                    <div class="text-4xl mb-2">🔍</div>
                    <h4 class="font-semibold text-xl mb-2">検索しやすい</h4>
                    <p class="text-bese">専門外来・症状・地域などから、簡単に条件を絞り込めます。</p>
                </div>

                <!-- カード2 -->
                <div class="p-4 border rounded-lg hover:shadow transition">
                    <div class="text-4xl mb-2">🗣️</div>
                    <h4 class="font-semibold text-xl mb-2">リアルな口コミ</h4>
                    <p class="text-base">実際に受診された方のリアルな声を参考に、安心して選べます。</p>
                </div>

                <!-- カード3 -->
                <div class="p-4 border rounded-lg hover:shadow transition">
                    <div class="text-4xl mb-2">⏰</div>
                    <h4 class="font-semibold text-xl mb-2">情報が豊富</h4>
                    <p class="text-base">診療時間や予約の有無など、必要な情報を一目で確認できます。</p>
                </div>

            </div>
        </div>
    </div>


    <section
        class="max-w-screen text-center py-12  py-12 px-4 bg-brand-100 bg-opacity-30 border-b border-gray-300 w-full">
        <h2 class="text-[32px] font-semibold text-gray-800 mb-4 mx-auto">病院を探す</h2>
        <p class="text-base text-gray-600 mb-6">
            条件を選んで、あなたに合った精神科・心療内科を検索しましょう。
        </p>
        <form method="GET" action="{{ route('hospital.result') }}"
            class="border-none p-4 flex flex-wrap justify-center gap-4 mb-6">
            <select name="specialty_id" class="border rounded px-4 py-2">
                <option value="">専門外来</option>
                @foreach ($specialties as $specialty)
                    <option value="{{ $specialty->id }}"
                        {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>{{ $specialty->name }}
                    </option>
                @endforeach
            </select>
            <select name="disorder_id" class="border rounded px-4 py-2">
                <option value="">疾患</option>
                @foreach ($disorders as $disorder)
                    <option value="{{ $disorder->id }}" {{ request('disorder_id') == $disorder->id ? 'selected' : '' }}>
                        {{ $disorder->name }}</option>
                @endforeach
            </select>

            <select name="prefecture" class="border rounded px-4 py-2 w-48">
                <option value="">都道府県</option>
                @foreach ($prefectures as $pref)
                    <option value="{{ $pref }}" {{ request('prefecture') == $pref ? 'selected' : '' }}>
                        {{ $pref }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="bg-green-600 text-white text-base px-6 py-2 rounded hover:bg-green-700">検索</button>
        </form>
    </section>


    <div class="bg-brand-100 bg-opacity-30 py-6 sm:py-8 lg:py-12">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">

            <div class="pb-12 text-center">
                <h2 class="text-[32px] font-semibold text-gray-800 lg:text-3xl">病院一覧</h2>
                @if ($hospitals->isEmpty())
                    <p class="text-red-500 font-bold">該当する病院は見つかりませんでした。</p>
                @endif
            </div>

            <div class="grid gap-x-4 gap-y-8 sm:grid-cols-2 md:gap-x-6 lg:grid-cols-2 xl:grid-cols-3">
                @foreach ($hospitals as $hospital)
                    <div>
                        <a href="{{ route('hospital.show', $hospital->id) }}"
                            class="group relative mb-2 block h-64 overflow-hidden rounded-lg bg-gray-100 lg:mb-3">
                            <img src="https://picsum.photos/seed/{{ uniqid() }}/{{ rand(400, 800) }}/{{ rand(300, 600) }}"
                                alt="{{ $hospital->name }}"
                                class="h-full w-full object-cover object-center transition duration-200 group-hover:scale-105" />
                        </a>
                        <div>
                            <h3 class="text-2xl font-medium text-gray-800 mb-2">{{ $hospital->name }}</h3>
                            <p class="text-base text-gray-600 mb-2"><strong>所在地：</strong>{{ $hospital->address }}</p>
                            <p class="text-base text-gray-600 mb-2">
                                <strong>診療時間：</strong>{{ $hospital->am_display }} {{ $hospital->pm_display }}
                            </p>
                            <p class="text-base text-gray-600">
                                <strong>対象疾患：</strong>
                                @foreach ($hospital->disorders as $disorder)
                                    {{ $disorder->name }}{{ !$loop->last ? '、' : '' }}
                                @endforeach
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-brand-100 bg-opacity-30 py-10 flex justify-center">
        {{ $hospitals->appends(request()->query())->links('vendor.pagination.tailwind') }}
    </div>

    <footer class="bg-white text-center py-6">
        <a href="{{ route('hospital.index') }}" class="text-base tetext-gray-800 font-bold hover:underline">
            精神科評価サイト
        </a>
    </footer>

</x-app-layout>



<!--病院情報を表示する
@if ($hospitals->isEmpty())
<p class="text-red-500 font-bold mb-6">該当する病院は見つかりませんでした。</p>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
  @foreach ($hospitals as $hospital)
<div class="bg-white rounded shadow p-4">
      <h3 class="text-lg font-bold mb-1">{{ $hospital->name }}</h3>
        <p class="text-sm text-gray-700 mb-1"><strong>所在地：</strong>{{ $hospital->address }}</p>
        <p class="text-sm text-gray-700 mb-1">
          <strong>診療時間：</strong>
          {{ $hospital->am_display }}
          {{ $hospital->pm_display }}
        </p>
        <p class="text-sm text-gray-700 mb-2">
          <strong>対象疾患：</strong>
          @foreach ($hospital->disorders as $disorder)
{{ $disorder->name }}{{ !$loop->last ? '、' : '' }}
@endforeach
        </p>
        <a href="{{ route('hospital.show', $hospital->id) }}" class="inline-block bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600 text-sm">詳細はこちら</a>
    </div>
@endforeach
</div>
-->
