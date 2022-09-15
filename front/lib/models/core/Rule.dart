class Rule {
  int id;
  String name;
  String type;
  bool mandatory;

  Rule({
    required this.id,
    required this.name,
    required this.type,
    required this.mandatory,
  });

  factory Rule.fromJson(Map<String, dynamic> json) {
    try {
      return Rule(
        id: json['id'],
        name: json['name'],
        type: json['type'],
        mandatory: json['mandatory'],
      );
    } catch (e) {
      print("catch rule $e");
      return Rule(
        id: 0,
        name: "",
        type: "",
        mandatory: false,
      );
    }
  }
}
