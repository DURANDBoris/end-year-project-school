import 'Document.dart';

import 'Rule.dart';

class Index {
  int id;
  String value;
  Rule rule;

  Index({
    required this.id,
    required this.value,
    required this.rule,
  });

  factory Index.fromJson(Map<String, dynamic> json) {
    try {
      return Index(
        id: json["id"],
        value: json["value"],
        rule: Rule.fromJson(
          json['rule'],
        ),
      );
    } catch (e) {
      print("catch index $e");
      return Index(
        id: 0,
        value: "",
        rule: Rule(
          id: 0,
          name: "",
          type: "",
          mandatory: false,
        ),
      );
    }
  }
}
