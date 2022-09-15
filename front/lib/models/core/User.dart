import 'Organization.dart';

class User {
  int id;
  String token;
  String firstname;
  String lastName;
  String email;
  List<Organization> listOrganization;

  User({
    required this.id,
    required this.token,
    required this.firstname,
    required this.lastName,
    required this.email,
    required this.listOrganization,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    Iterable list = json["organizations"];
    try {
      return User(
        id: json["user"]["id"],
        token: json["token"],
        firstname: json["user"]["firstname"].toString(),
        lastName: json["user"]["lastName"].toString(),
        email: json["user"]["email"].toString(),
        listOrganization: json["organizations"].isEmpty
            ? []
            : list.map((i) => Organization.fromJson(i)).toList(),
      );
    } catch (e) {
      print("catch user $e");
      return User(
          id: 0,
          token: "",
          firstname: "",
          lastName: "",
          email: "",
          listOrganization: []);
    }
  }
}
