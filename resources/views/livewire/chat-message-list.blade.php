<div>
    @foreach($messages as $message)
        <div>
            <strong>{{ $message['role'] }}:</strong> {{ $message['content'] }}
        </div>
    @endforeach
</div>
