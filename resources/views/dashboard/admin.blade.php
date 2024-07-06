<x-layout documentTitle="Admin Dashbord">
    <h1>Admin Dashbord</h1>
    @foreach ($groups as $group)
        <h1>{{$group->nome}}</h1>
    @endforeach
    @foreach ($aliases as $alias)
        <h1>{{$alias->data_allenamento}}</h1>
    @endforeach
</x-layout>
