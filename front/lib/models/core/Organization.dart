import 'UserOrg.dart';

class Organization {
  int id;
  String name;
  UserOrg owner;

  Organization({
    required this.id,
    required this.name,
    required this.owner,
  });

  factory Organization.fromJson(Map<String, dynamic> json) {
    try {
      return Organization(
        id: json["id"],
        name: json["name"].toString(),
        owner: UserOrg.fromJson(json['owner']),
      );
    } catch (e) {
      print("catch organization");
      return Organization(
        id: 0,
        name: "",
        owner: UserOrg.fromJson(json['owner']),
      );
    }
  }
}
