<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chat</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow-lg overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
          <h1 class="text-2xl font-semibold">Chat</h1>
          <div class="flex items-center space-x-4">
            <a href="{{ url()->previous() }}" class="text-sm text-gray-700">Back</a>
          </div>
        </div>

        <main class="p-6">
          @if(!$other)
            <div class="p-4 border rounded">No chat partner available.</div>
          @else
            <div class="mb-4">
              <div class="font-semibold">Chat with: {{ $other->name }} ({{ $other->subject_course ?? 'N/A' }})</div>
            </div>

            <div id="messages" class="border rounded p-4 bg-gray-50 h-64 overflow-y-auto">
              @foreach($messages as $m)
                @if($m->from_user_id === auth()->id())
                  <div class="mb-2 text-right">
                    <div class="inline-block bg-blue-600 text-white px-3 py-2 rounded">{{ $m->message }}</div>
                    <div class="text-xs text-gray-400">{{ $m->created_at->diffForHumans() }}</div>
                  </div>
                @else
                  <div class="mb-2 text-left">
                    <div class="inline-block bg-gray-200 text-gray-900 px-3 py-2 rounded">{{ $m->message }}</div>
                    <div class="text-xs text-gray-400">{{ $m->created_at->diffForHumans() }}</div>
                  </div>
                @endif
              @endforeach
            </div>

            <form id="sendForm" method="POST" action="{{ route('chat.send') }}" class="mt-4 flex">
              @csrf
              <input type="hidden" name="to_user_id" value="{{ $other->id }}">
              <input id="messageInput" name="message" class="flex-1 border rounded p-2 mr-2" placeholder="Write a message..." autocomplete="off">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send</button>
            </form>

            <script>
              const otherId = {{ $other->id }};
              const messagesEl = document.getElementById('messages');
              const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

              // Polling for new messages every 3 seconds
              setInterval(async () => {
                try {
                  const res = await fetch('{{ route('chat.messages') }}?other_id=' + otherId, {headers: {'X-Requested-With':'XMLHttpRequest'}});
                  if (!res.ok) return;
                  const data = await res.json();
                  // render messages
                  messagesEl.innerHTML = '';
                  data.forEach(m => {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('mb-2');
                    if (m.from_user_id === {{ auth()->id() }}) {
                      wrapper.style.textAlign = 'right';
                      wrapper.innerHTML = `<div class="inline-block bg-blue-600 text-white px-3 py-2 rounded">${escapeHtml(m.message)}</div><div class="text-xs text-gray-400">${new Date(m.created_at).toLocaleString()}</div>`;
                    } else {
                      wrapper.style.textAlign = 'left';
                      wrapper.innerHTML = `<div class="inline-block bg-gray-200 text-gray-900 px-3 py-2 rounded">${escapeHtml(m.message)}</div><div class="text-xs text-gray-400">${new Date(m.created_at).toLocaleString()}</div>`;
                    }
                    messagesEl.appendChild(wrapper);
                  });
                  messagesEl.scrollTop = messagesEl.scrollHeight;
                } catch (e) {
                  // ignore polling errors
                }
              }, 3000);

              // simple escaper
              function escapeHtml(unsafe) {
                return unsafe
                     .replace(/&/g, "&amp;")
                     .replace(/</g, "&lt;")
                     .replace(/>/g, "&gt;")
                     .replace(/\"/g, "&quot;")
                     .replace(/'/g, "&#039;");
              }

              // scroll to bottom initially
              messagesEl.scrollTop = messagesEl.scrollHeight;
            </script>
          @endif
        </main>
      </div>
    </div>
  </body>
</html>
