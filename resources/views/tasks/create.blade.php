<div class="container">
    <h2>Create tasks</h2>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>