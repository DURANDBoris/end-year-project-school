class Folder {
  int id;
  int iconIndex;
  String name;

  Folder({
    required this.id,
    required this.iconIndex,
    required this.name,
  });

  factory Folder.fromJson(Map<String, dynamic> json) {
    try {
      return Folder(
        id: json["id"],
        iconIndex: json["icon"],
        name: json["name"],
      );
    } catch (e) {
      print("catch folder $e");
      return Folder(
        id: 0,
        iconIndex: 0,
        name: "",
      );
    }
  }
}
